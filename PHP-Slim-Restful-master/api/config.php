<?php
//ob_start("ob_gzhandler");
error_reporting(0);
session_start();

/* DATABASE CONFIGURATION */
define('DB_SERVER', 'localhost:3306');
define('DB_USERNAME', 'forevco_forev');
define('DB_PASSWORD', '8TqIH#_6c+=$');
define('DB_DATABASE', 'forevco_katalog');
define("BASE_URL", "http://katalog.forev.com.tr/PHP-Slim-Restful-master/api/");
define("SITE_KEY", 'yourSecretKey');


function getDB() 
{
	$dbhost=DB_SERVER;
	$dbuser=DB_USERNAME;
	$dbpass=DB_PASSWORD;
	$dbname=DB_DATABASE;
	$dbConnection = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbConnection->exec("set names utf8");
	$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbConnection;
}
/* DATABASE CONFIGURATION END */

/* API key encryption */
function apiToken($session_uid)
{
$key=md5(SITE_KEY.$session_uid);
return hash('sha256', $key);
}



?>