<?php
/**
 * Post a subscriber export
 * 
 * @author Aaron Saray
 */

/** @var \iMoneza\Connection $connection */
$connection = require '_build-connection.php';

$options = new \iMoneza\Options\Management\ExternalSubscriberExport();
$options->setApiBaseURL(getenv('MANAGEMENT_API_URL')); // only for testing

$options->setStartDate(new \DateTime('2016-04-20 00:00:00'))->setEndDate(new \DateTime('2016-04-20 23:59:59'));

$result = $connection->request($options, $options->getDataObject());

print "Callback ID:\n";
print $result->getID();
print "\n";
