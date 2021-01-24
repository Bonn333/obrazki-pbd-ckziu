<?php

date_default_timezone_set('Europe/Warsaw');

$config = [
    'host' => 'localhost',
    'user' => 'obrazki',
    'pass' => 'obrazki',
    'name' => 'obrazki'
];

return new PDO("mysql:host={$config['host']};dbname={$config['name']}", $config['user'], $config['pass']);