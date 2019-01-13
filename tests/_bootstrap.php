<?php

define('YII_ENV', 'test');
defined('YII_DEBUG') or define('YII_DEBUG', false);

define('ROOT', dirname(__DIR__));
define('VENDOR_DIR', ROOT.'/vendor');

while (!file_exists(VENDOR_DIR . '/autoload.php')) {
    throw new \Exception('Failed to locate autoload.php');
}


require_once VENDOR_DIR . '/autoload.php';
require VENDOR_DIR . '/yiisoft/yii2/Yii.php';