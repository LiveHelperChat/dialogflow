# Instructions how to test things manually

Sample curl command. Replace `BEARER_TOKEN`, `PROJECT_ID`, `YOU_CHAT_ID` with your variables

* Fill `service-account.json` file with your service credentials file.
* run `php dialogflow.php` and you will get bearer token.
* Adjust below CURL and try it out to see does it works.

```shell
curl -X POST \
-H "Content-Type: application/json; charset=utf-8" \
-H "Authorization: Bearer <BEARER_TOKEN>" \
-d '{
  "queryInput": {
    "text": {
      "text": "Hi",
      "languageCode": "en-US"
    }
  }
}' "https://dialogflow.googleapis.com/v2/projects/<PROJECT_ID>/agent/sessions/<YOU_CHAT_ID>:detectIntent"
```