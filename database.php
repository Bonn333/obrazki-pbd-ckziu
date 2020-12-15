<?php

date_default_timezone_set('Europe/Warsaw');

$config = [
    'host' => 'localhost',
    'user' => 'root',
    'pass' => 'heheszki',
    'name' => 'obrazki'
];

return new PDO("mysql:host={$config['host']};dbname={$config['name']}", $config['user'], $config['pass']);