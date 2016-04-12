<?php
/**
 * Get all resources from a property
 * 
 * @author Aaron Saray
 */

/** @var \iMoneza\Connection $connection */
$connection = require '_build-connection.php';

$options = new \iMoneza\Options\Management\GetResourcesFromProperty();
$options->setApiBaseURL(getenv('MANAGEMENT_API_URL')); // only for testing

$result = $connection->request($options, $options->getDataObject());

print "Here is the result:\n";
var_dump($result);