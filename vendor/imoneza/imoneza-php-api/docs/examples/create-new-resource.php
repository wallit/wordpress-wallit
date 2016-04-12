<?php
/**
 * Create a new resource
 * 
 * @author Aaron Saray
 */

/** @var \iMoneza\Connection $connection */
$connection = require '_build-connection.php';

$options = new \iMoneza\Options\Management\SaveResource();
$options->setApiBaseURL(getenv('MANAGEMENT_API_URL')); // only for testing

$externalId = uniqid();
$name = 'NAME:' . $externalId;
$title = 'TITLE:' . $externalId;
$options->setExternalKey($externalId)->setName($name)->setTitle($title)->setPricingGroupId('b527e274-5ae1-425f-ad8f-320dd369452d');

$result = $connection->request($options, $options->getDataObject());

print "Here is the result:\n";
var_dump($result);