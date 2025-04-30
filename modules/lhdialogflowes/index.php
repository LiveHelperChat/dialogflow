<?php

$tpl = erLhcoreClassTemplate::getInstance('lhdialogflowes/index.tpl.php');

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
    array(
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('dialogflow/module', 'Dialogflow')
    )
);

?>