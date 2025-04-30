# Dialogflow Essential Extension for LiveHelperChat

This extension enables LiveHelperChat to authenticate and communicate with Google's Dialogflow ES (Essential) service, allowing chatbot integration with natural language processing capabilities.

## Overview

The Dialogflow Essential extension provides an authentication mechanism for LiveHelperChat to securely connect with Google's Dialogflow ES API. This allows you to:

- Generate and manage OAuth bearer tokens for Dialogflow API authentication
- Set up service credentials for Google Cloud Platform
- Use Dialogflow's natural language understanding in your chat workflows
- Integrate AI-powered conversation capabilities into your LiveHelperChat installation

## Installation

1. Upload the extension to your LiveHelperChat installation's `extension` folder
2. Activate the extension in your LiveHelperChat settings `settings/settings.ini.php` 

```
'extensions' => array ('dialogflowes')
```

## Setup Instructions

### Step 1: Create a Google Cloud Project

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select an existing one
3. Enable the Dialogflow API for your project

### Step 2: Create Service Account Credentials

1. In your Google Cloud project, navigate to "IAM & Admin" > "Service Accounts"
2. Create a new service account with appropriate permissions (Dialogflow API access)
3. Generate and download a JSON key file for this service account

### Step 3: Configure the Extension

1. Log in to your LiveHelperChat admin panel
2. Navigate to "Dialogflow" in the left menu under modules
3. Click on "Options"
4. Paste the entire content of your service account JSON file into the "Service Credentials Content" field
5. Save the settings

### Step 4: Import Rest API and Bot

1. Import doc/bot/rest-api.json file as Rest API
2. Import doc/bot/bot.json as bot while choosing just imported Rest API
3. Modify in back office Rest API and change `PROJECT_ID` to yours.

## Usage

Once configured, the extension will handle OAuth authentication automatically. To use Dialogflow in your LiveHelperChat environment:

1. In REST API calls or webhooks, use the placeholder `{{dialogflowes_bearer_token}}` where bearer token authentication is needed
2. The extension will automatically replace this placeholder with a valid, fresh bearer token
3. Tokens are cached until expiration and automatically renewed when needed

## Manual testing

See doc folder content.

## Permissions

To access and configure this extension, the LiveHelperChat user needs the `lhdialogflowes` > `use_options` permission.

## Troubleshooting

- If authentication fails, try resetting the bearer token in the Options page
- Ensure your service account has the proper permissions in Google Cloud
- Check that the service credentials JSON is properly formatted and complete
- Verify that your Google Cloud project has Dialogflow API enabled