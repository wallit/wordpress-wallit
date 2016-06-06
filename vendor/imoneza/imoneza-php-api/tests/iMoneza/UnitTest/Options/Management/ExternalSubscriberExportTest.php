<?php
/**
 * test
 *
 * @author Aaron Saray
 */

namespace iMoneza\UnitTest\Options\Management;
use iMoneza\Options\Management\ExternalSubscriberExport;


/**
 * Class ExternalSubscriberExportTest
 * @package iMoneza\Tests\Options\Management
 */
class ExternalSubscriberExportTest extends \PHPUnit_Framework_TestCase
{
    public function testGetRequestType()
    {
        $options = new ExternalSubscriberExport();
        $this->assertEquals('post', $options->getRequestType());
    }

    public function testGetEndPoint()
    {
        $options = new ExternalSubscriberExport();
        $options->setAccessKey('access-key');
        $this->assertEquals('/api/Property/access-key/ExternalSubscriberExport', $options->getEndPoint());
    }

    public function testGetDataObject()
    {
        $options = new ExternalSubscriberExport();
        $this->assertInstanceOf('iMoneza\Data\ExternalSubscriberExportResponse', $options->getDataObject());
    }

    public function testSetStartDate()
    {
        $options = new ExternalSubscriberExport();
        $date = new \DateTime('2014-01-01 00:00:00', new \DateTimeZone('America/Chicago'));
        $this->assertInstanceOf('iMoneza\Options\Management\ExternalSubscriberExport', $options->setStartDate($date));
        $this->assertAttributeEquals('2014-01-01T00:00:00-06:00', 'StartDate', $options);
    }

    public function testSetEndDate()
    {
        $options = new ExternalSubscriberExport();
        $date = new \DateTime('2014-04-01 00:00:00', new \DateTimeZone('America/Chicago'));
        $this->assertInstanceOf('iMoneza\Options\Management\ExternalSubscriberExport', $options->setEndDate($date));
        $this->assertAttributeEquals('2014-04-01T00:00:00-05:00', 'EndDate', $options);
    }
}