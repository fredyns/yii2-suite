<?php
$localDevelopment = (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] == 'localhost');
$environment = $localDevelopment ? 'dev' : 'production';

defined('YII_DEBUG') or define('YII_DEBUG', $localDevelopment);
defined('YII_ENV') or define('YII_ENV', $environment);
