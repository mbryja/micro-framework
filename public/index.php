<?php
namespace your\namespace;

error_reporting(E_ERROR | E_PARSE | E_USER_ERROR |E_USER_WARNING |E_COMPILE_ERROR|E_COMPILE_WARNING|E_CORE_ERROR|E_CORE_WARNING|E_WARNING);
ini_set('display_errors', 0);

require_once('../vendor/autoload.php');
require_once('../app/App/Constants.php');

header('Vary: Accept');
if (isset($_SERVER['HTTP_ACCEPT']) &&
    (strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)) {
    header('Content-type: application/json');
}

$app = new App();
$app->config["location.webroot"] = dirname(__FILE__);
$app->init();
