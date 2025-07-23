<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
define("WEB_TITLE","West Bridge Trust PLC"); // Change Bank Name
define("WEB_URL","https://dashboard.westbridgetrust.com"); // Change No "/" Ending splash
define("WEB_EMAIL","contact@westbridgetrust.com"); // Change Your Website Email

$web_url = WEB_URL;
function support_plugin(){
    require_once 'support_plugin.php';
}

function dbConnect(){
    $servername = "localhost";
    $username = "multistream6_westbridgetrust_back";//DATABASE USERNAME
    $password = "westbridgetrust_back";//DATABASE PASSWORD
    $database = "multistream6_westbridgetrust_back";//DATABASE NAME
    $dns = "mysql:host=$servername;dbname=$database";

    try {
        $conn = new PDO($dns, $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
