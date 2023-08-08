<?php
/**
*  DEBUGGING : XDEBUG_SESSION=thunder
*/

declare(strict_types=1);


require_once __DIR__ . "/Parameters.php";

$requestUri = $_SERVER["REQUEST_URI"];
$requestUri = preg_replace("/(\?)*XDEBUG_SESSION=thunder/", "",$requestUri);

$parts = explode("/",$requestUri);


if ($parts[1] != "products") {

    http_response_code(404);
}


$id = $parts[2] ?? null;



$database = new Database($_SERVER["MySql_Server"], $_SERVER["MySql_DB"],$_SERVER["MySql_Login"] ,$_SERVER["MySql_Password"] );

$gateway = new ProductGateway($database);

$controller = new ProductController($gateway);

$controller->processRequest($_SERVER["REQUEST_METHOD"], $id);