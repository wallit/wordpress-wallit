<?php
/**
 * Get resource model from a resource key
 * 
 * @author Aaron Saray
 */

/** @var \iMoneza\Connection $connection */
$connection = require '_build-connection.php';

$options = new \iMoneza\Options\Management\GetResource();
$options->setApiBaseURL(getenv('MANAGEMENT_API_URL')); // only for testing
$options->setResourceKey('56cf1eb6731e0');

$result = $connection->request($options, $options->getDataObject());

print "Here is the result:\n";
var_dump($result);