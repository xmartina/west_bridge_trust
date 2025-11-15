<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
define("WEB_TITLE","West Bridge Trust PLC"); // Change Bank Name
define("WEB_URL","https://dashboard.westbridgetrust.com"); // Change No "/" Ending splash
define("WEB_EMAIL","contact@westbridgetrust.com"); // Change Your Website Email

if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__) . '/');
}
if (!defined('INCLUDE_PATH')) {
    define('INCLUDE_PATH', __DIR__ . '/');
}
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', BASE_PATH);
}

$web_url = WEB_URL;
function support_plugin(){
    require_once INCLUDE_PATH . 'support_plugin.php';
}

function dbConnect(){
    $servername = "localhost";
    $username = "dashboard_westbridgetrust";//DATABASE USERNAME
    $password = "dashboard_westbridgetrust";//DATABASE PASSWORD
    $database = "dashboard_westbridgetrust";//DATABASE NAME
    $dns = "mysql:host=$servername;dbname=$database";

    try {
        $conn = new PDO($dns, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        return $conn;
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}
//return dbConnect();

function inputValidation($value): string
{
    return trim(htmlspecialchars(htmlentities($value)));
}
