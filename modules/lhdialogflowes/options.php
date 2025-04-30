<?php

$tpl = erLhcoreClassTemplate::getInstance('lhdialogflowes/options.tpl.php');

$dialogOptions = erLhcoreClassModelChatConfig::fetch('dialogflowes_options');
$data = (array)$dialogOptions->data;

if ( isset($_POST['StoreOptions'])  ) {

    if (!isset($_POST['csfr_token']) || !$currentUser->validateCSFRToken($_POST['csfr_token'])) {
        erLhcoreClassModule::redirect('dialogflowes/options');
        exit;
    }

    $definition = array(
        'service_credentials' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        ),
        'reset' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'boolean'
        )
    );

    $form = new ezcInputForm( INPUT_POST, $definition );
    $Errors = array();

    if ( $form->hasValidData( 'service_credentials' )) {
        $data['service_credentials'] = $form->service_credentials;
    } else {
        $data['service_credentials'] = '';
    }

    if ( $form->hasValidData( 'reset' )) {
        $data['dialogflow_es_bearer'] = '';
        $data['dialogflow_es_expires_in'] = 0;
    }

    $dialogOptions->explain = '';
    $dialogOptions->type = 0;
    $dialogOptions->hidden = 1;
    $dialogOptions->identifier = 'dialogflowes_options';
    $dialogOptions->value = serialize($data);
    $dialogOptions->saveThis();

    $tpl->set('updated','done');
}

$tpl->set('dialog_options',$data);

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
    array(
        'url' => erLhcoreClassDesign::baseurl('dialogflowes/index'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('dialogflow/module', 'Dialogflow')
    ),
    array(
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('dialogflow/module', 'Options')
    )
);

?>