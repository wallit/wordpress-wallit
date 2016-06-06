<?php
/**
 * Post a subscriber export
 * 
 * @author Aaron Saray
 */

/** @var \iMoneza\Connection $connection */
$connection = require '_build-connection.php';

$options = new \iMoneza\Options\Management\CallbackResult();
$options->setApiBaseURL(getenv('MANAGEMENT_API_URL')); // only for testing

$options->setCallbackToken("5d0f5ca5-2528-4bbd-b83f-18b3a732c1ee");

$result = $connection->request($options, $options->getDataObject());

print "Here is the result:\n";
var_dump($result);