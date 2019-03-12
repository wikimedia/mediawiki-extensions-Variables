<?php

$config = require __DIR__ . '/../vendor/mediawiki/mediawiki-phan-config/src/config.php';
// Due to creation of Parser::$mExtVariables property
$config['suppress_issue_types'][] = 'PhanUndeclaredProperty';

return $config;
