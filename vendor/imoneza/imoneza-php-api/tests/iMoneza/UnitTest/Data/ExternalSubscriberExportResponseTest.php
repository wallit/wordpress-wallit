<?php
/**
 * Test
 *
 * @author Aaron Saray
 */

namespace iMoneza\UnitTest\Data;
use iMoneza\Data\ExternalSubscriberExportResponse;


/**
 * Class ExternalSubscriberExportResponseTest
 * @package iMoneza\UnitTest\Data
 */
class ExternalSubscriberExportResponseTest extends \PHPUnit_Framework_TestCase
{
    public function testId()
    {
        $property = new ExternalSubscriberExportResponse();
        $this->assertNull($property->getId());
        $this->assertInstanceOf('iMoneza\Data\ExternalSubscriberExportResponse', $property->setID('some-value-here'));
        $this->assertEquals('some-value-here', $property->getId());
    }

    public function testNotificationEmailAddress()
    {
        $property = new ExternalSubscriberExportResponse();
        $this->assertNull($property->getNotificationEmailAddress());
        $this->assertInstanceOf('iMoneza\Data\ExternalSubscriberExportResponse', $property->setNotificationEmailAddress('item@here.com'));
        $this->assertEquals('item@here.com', $property->getNotificationEmailAddress());
    }

    public function testStatus()
    {
        $property = new ExternalSubscriberExportResponse();
        $this->assertNull($property->getStatus());
        $this->assertInstanceOf('iMoneza\Data\ExternalSubscriberExportResponse', $property->setStatus(ExternalSubscriberExportResponse::STATUS_QUEUED));
        $this->assertEquals(ExternalSubscriberExportResponse::STATUS_QUEUED, $property->getStatus());
    }

    public function testStartDate()
    {
        $property = new ExternalSubscriberExportResponse();
        $this->assertNull($property->getStartDate());
        $this->assertInstanceOf('iMoneza\Data\ExternalSubscriberExportResponse', $property->setStartDate('2016-01-01 00:00:00'));
        $this->assertEquals(new \DateTime('2016-01-01 00:00:00'), $property->getStartDate());
    }

    public function testEndDate()
    {
        $property = new ExternalSubscriberExportResponse();
        $this->assertNull($property->getEndDate());
        $this->assertInstanceOf('iMoneza\Data\ExternalSubscriberExportResponse', $property->setEndDate('2016-04-01 00:00:00'));
        $this->assertEquals(new \DateTime('2016-04-01 00:00:00'), $property->getEndDate());
    }

}
