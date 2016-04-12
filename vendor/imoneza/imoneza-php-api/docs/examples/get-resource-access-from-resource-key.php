<?php
/**
 * Get resource access model from a resource key
 * 
 * @author Aaron Saray
 */

/** @var \iMoneza\Connection $connection */
$connection = require '_build-connection.php';

$options = new \iMoneza\Options\Access\GetResourceFromResourceKey();
$options->setApiBaseURL(getenv('ACCESS_API_URL')); // only for testing
$options->setResourceKey('x')->setIP(\iMoneza\Helper::getCurrentIP())
    ->setResourceURL('x');

$result = $connection->request($options, $options->getDataObject());

print "Here is the result:\n";
var_dump($result);