<?php

$Module = array( "name" => "Dialogflow Essential");

$ViewList = array();

$ViewList['options'] = array(
    'params' => array(),
    'uparams' => array(),
    'functions' => array('use_options')
);

$ViewList['index'] = array(
    'params' => array(),
    'uparams' => array(),
    'functions' => array('use_options')
);

$FunctionList['use_options'] = array('explain' => 'Allow operator to set credentials file content');
