<?php

class erLhcoreClassExtensionDialogflowes
{

    public function run()
    {
        $dispatcher = erLhcoreClassChatEventDispatcher::getInstance();
        $dispatcher->listen('chat.rest_api_before_request', array(
            $this,
            'addVariables'
        ));
    }

    public function addVariables($params)
    {
        if (isset($params['method']['auth_bearer']) && str_contains($params['method']['auth_bearer'], '{{dialogflowes_bearer_token}}')) {
            $params['method']['auth_bearer'] = \LiveHelperChatExtension\dialogflowes\providers\erLhcoreClassDialogflowESValidator::getBearerToken();
        }
    }

}


