<?php

use tests\_support\Env;

$db = [
    'class'       => yii\db\Connection::class,
    'dsn'         => 'mysql' . ':'
        . 'host=' . Env::get('MYSQL_HOST', 'mysql') . ';'
        . 'port=' . Env::get('MYSQL_PORT', '3306') . ';'
        . 'dbname=' . Env::get('MYSQL_DATABASE', 'app_test'),
    'username'    => Env::get('MYSQL_USER', 'user'),
    'password'    => Env::get('MYSQL_PASSWORD', 'pass'),
    'charset'     => 'utf8mb4',
    'tablePrefix' => Env::get('MYSQL_DB_PREFIX', 'prefix_'),
];

if (file_exists(__DIR__ . '/db.local.php')) {
    $db = array_merge($db, require(__DIR__ . '/db.local.php'));
}

return $db;