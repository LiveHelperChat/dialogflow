<?php

namespace LiveHelperChatExtension\dialogflowes\providers;

class erLhcoreClassDialogflowESValidator
{
    public static function getBearerToken()
    {
        $dialogOptions = \erLhcoreClassModelChatConfig::fetch('dialogflowes_options');
        $data = (array)$dialogOptions->data;

        // Use present
        if (!empty($data['dialogflow_es_bearer']) && !empty($data['dialogflow_es_expires_in']) && $data['dialogflow_es_expires_in'] > time()) {
            return $data['dialogflow_es_bearer'];
        }

        $scopes = [
            //'https://www.googleapis.com/auth/cloud-platform', // Broad scope, includes Dialogflow
            'https://www.googleapis.com/auth/dialogflow'    // Specific Dialogflow scope
        ];
        
        $tokenValiditySeconds = 3600; // How long the requested token should be valid (max 1 hour typically)

        function base64UrlEncode($input)
        {
            // Standard Base64 encode, then replace '+' with '-', '/' with '_', remove '=' padding
            return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
        }

        try {

            if (!extension_loaded('openssl')) {
                throw new \Exception("Error: The 'openssl' PHP extension is required but not loaded. Please enable it in your php.ini.");
            }

            if (!isset($data['service_credentials'])) {
                throw new \Exception("Please enter service credentials");
            }

            // 1. Read and decode the Service Account Key File
            $keyFileData = $data['service_credentials'];

            $keyFileJson = json_decode($keyFileData, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception("Error decoding service account key file JSON: " . json_last_error_msg());
            }

            // 2. Extract necessary credentials from the key file
            $privateKey = $keyFileJson['private_key'] ?? null;
            $clientEmail = $keyFileJson['client_email'] ?? null; // This is the issuer (iss)
            $tokenUri = $keyFileJson['token_uri'] ?? null;       // This is the audience (aud) and token endpoint

            if (!$privateKey || !$clientEmail || !$tokenUri) {
                throw new \Exception("Key file is missing required fields: 'private_key', 'client_email', or 'token_uri'.");
            }

            // 3. Construct the JWT (JSON Web Token) Header
            // Specifies the signing algorithm (RS256) and token type (JWT)
            $jwtHeader = ['alg' => 'RS256', 'typ' => 'JWT'];
            $encodedJwtHeader = base64UrlEncode(json_encode($jwtHeader));

            // 4. Construct the JWT Claims Set (Payload)
            $currentTime = time();
            $expirationTime = $currentTime + $tokenValiditySeconds;
            $jwtClaims = [
                'iss' => $clientEmail,                 // Issuer: Service account email
                'scope' => implode(' ', $scopes),      // Scopes: Space-separated string of requested permissions
                'aud' => $tokenUri,                    // Audience: The token endpoint URI
                'exp' => $expirationTime,              // Expiration time: Timestamp when the token expires
                'iat' => $currentTime                  // Issued at time: Timestamp when the token was created
            ];
            $encodedJwtClaims = base64UrlEncode(json_encode($jwtClaims));

            // 5. Create the signing input string
            // This is the Base64Url-encoded header and claims, joined by a dot
            $signingInput = $encodedJwtHeader . '.' . $encodedJwtClaims;

            // 6. Sign the input using the private key with RSA-SHA256
            $signatureBytes = '';
            // Use openssl_sign with OPENSSL_ALGO_SHA256 for RS256
            if (!openssl_sign($signingInput, $signatureBytes, $privateKey, OPENSSL_ALGO_SHA256)) {
                throw new \Exception("Failed to sign JWT: " . openssl_error_string());
            }
            $encodedSignature = base64UrlEncode($signatureBytes);

            // 7. Assemble the final JWT string (assertion)
            // Format: {Base64UrlEncodedHeader}.{Base64UrlEncodedClaims}.{Base64UrlEncodedSignature}
            $jwtAssertion = $signingInput . '.' . $encodedSignature;

            // 8. Prepare the POST data for the token endpoint request
            // Google's OAuth 2.0 requires a specific grant type for service account JWTs
            $postData = http_build_query([
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwtAssertion
            ]);

            // 9. Make the HTTP POST request to the token endpoint using cURL
            $ch = curl_init();
            if ($ch === false) {
                throw new \Exception("Failed to initialize cURL.");
            }

            curl_setopt($ch, CURLOPT_URL, $tokenUri); // Set the target URL (Google's token endpoint)
            curl_setopt($ch, CURLOPT_POST, true); // Specify POST request method
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData); // Attach the POST data
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string instead of outputting it
            curl_setopt($ch, CURLOPT_HTTPHEADER, [ // Set necessary HTTP headers
                'Content-Type: application/x-www-form-urlencoded',
                'Content-Length: ' . strlen($postData)
            ]);
            // Optional: Set timeout values
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);

            $responseBody = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            // 10. Process the response from the token endpoint
            if ($responseBody === false) {
                throw new \Exception("cURL error during token request: " . $curlError);
            }

            $responseData = json_decode($responseBody, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception("Failed to decode JSON response from token endpoint. Response: " . $responseBody);
            }

            // Check for successful HTTP status code and presence of access_token
            if ($httpCode !== 200 || !isset($responseData['access_token'])) {
                $errorDescription = $responseData['error_description'] ?? ($responseData['error'] ?? 'Unknown error');
                throw new \Exception("Error retrieving access token (HTTP $httpCode): " . $errorDescription);
            }

            $data['dialogflow_es_expires_in'] = time() + (int)$responseData['expires_in'] - 30;
            $data['dialogflow_es_bearer'] = $responseData['access_token'];

            $dialogOptions->value = serialize($data);
            $dialogOptions->saveThis();

            return $responseData['access_token'];

        } catch (Exception $e) {
            // Catch any exceptions thrown during the process
            return $e->getMessage();
        }
    }
}

?>