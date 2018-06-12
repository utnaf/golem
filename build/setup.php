<?php

$rootDir = dirname(dirname(__FILE__));
$appname = basename($rootDir);

$filesToReplace = [
    $rootDir . '/build/docker-compose.yml'
];

array_map(function($file) use ($appname) {
    $content = file_get_contents($file);
    file_put_contents($file, str_replace('{appname}', $appname, $content));
}, $filesToReplace);

if (!file_exists('.env')) {
    copy('.env.example', '.env');
}
