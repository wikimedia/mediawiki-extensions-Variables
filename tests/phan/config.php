<?php

$config = require __DIR__ . '/../../vendor/mediawiki/mediawiki-phan-config/src/config.php';
$config['file_list'][] = 'ExtVariables.class.php';
// Due to creation of Parser::$mExtVariables property
$config['suppress_issue_types'][] = 'PhanUndeclaredProperty';

return $config;
