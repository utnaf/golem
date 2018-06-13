<?php

$rootDir = dirname(dirname(__FILE__));
$appname = preg_replace('[^\s]*', '', basename($rootDir));

$filesToReplace = [
    $rootDir . '/build/docker-compose.yml',
    $rootDir . '/.env',
];

array_map(function($file) use ($appname) {
    $content = file_get_contents($file);
    file_put_contents($file, str_replace('{appname}', $appname, $content));
}, $filesToReplace);
