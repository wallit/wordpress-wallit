<?php
/**
 * Bootstrap and build the connection for these examples
 *
 * @author Aaron Saray
 */

require __DIR__ . '/../../vendor/autoload.php';

$dotenv = new \Dotenv\Dotenv(__DIR__);
$dotenv->load();

$accessApiKey = getenv('ACCESS_API_KEY');
$accessSecretKey = getenv('ACCESS_API_SECRET');
$apiKey = getenv('MANAGEMENT_API_KEY');
$secretKey = getenv('MANAGEMENT_API_SECRET');

$logger = new \Monolog\Logger(__FILE__);
$logger->pushHandler(new \Monolog\Handler\StreamHandler('php://stdout'));

return new \iMoneza\Connection($apiKey, $secretKey, $accessApiKey, $accessSecretKey, new \iMoneza\Request\Curl(), $logger);