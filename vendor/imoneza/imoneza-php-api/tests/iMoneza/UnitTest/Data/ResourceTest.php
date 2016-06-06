<?php
/**
 * Test
 *
 * @author Aaron Saray
 */

namespace iMoneza\UnitTest\Data;
use iMoneza\Data\PricingGroup;
use iMoneza\Data\Property;
use iMoneza\Data\Resource;


/**
 * Class ResourceTest
 * @package iMoneza\UnitTest\Data
 */
class ResourceTest extends \PHPUnit_Framework_TestCase
{
    public function testName()
    {
        $resource = new Resource();
        $this->assertNull($resource->getName());
        $this->assertInstanceOf('iMoneza\Data\Resource', $resource->setName('my name'));
        $this->assertEquals('my name', $resource->getName());
    }

    public function testExternalKey()
    {
        $resource = new Resource();
        $this->assertNull($resource->getExternalKey());
        $this->assertInstanceOf('iMoneza\Data\Resource', $resource->setExternalKey('some-key'));
        $this->assertEquals('some-key', $resource->getExternalKey());
    }

    public function testActive()
    {
        $resource = new Resource();
        $this->assertNull($resource->isActive());
        $this->assertInstanceOf('iMoneza\Data\Resource', $resource->setActive(true));
        $this->assertTrue($resource->isActive());
    }

    public function testUrl()
    {
        $resource = new Resource();
        $this->assertNull($resource->getURL());
        $this->assertInstanceOf('iMoneza\Data\Resource', $resource->setURL('the-url'));
        $this->assertEquals('the-url', $resource->getURL());
    }

    public function testTitle()
    {
        $resource = new Resource();
        $this->assertNull($resource->getTitle());
        $this->assertInstanceOf('iMoneza\Data\Resource', $resource->setTitle('a title here'));
        $this->assertEquals('a title here', $resource->getTitle());
    }

    public function testDescription()
    {
        $resource = new Resource();
        $this->assertNull($resource->getDescription());
        $this->assertInstanceOf('iMoneza\Data\Resource', $resource->setDescription('some description'));
        $this->assertEquals('some description', $resource->getDescription());
    }

    public function testByline()
    {
        $resource = new Resource();
        $this->assertNull($resource->getByline());
        $this->assertInstanceOf('iMoneza\Data\Resource', $resource->setByline('by aaron'));
        $this->assertEquals('by aaron', $resource->getByline());
    }

    public function testPublicationDate()
    {
        $resource = new Resource();
        $this->assertNull($resource->getPublicationDate());
        $date = new \DateTime();
        $this->assertInstanceOf('iMoneza\Data\Resource', $resource->setPublicationDate($date));
        $this->assertEquals($date, $resource->getPublicationDate());
    }

    public function testPricingGroup()
    {
        $resource = new Resource();
        $this->assertNull($resource->getPricingGroup());
        $pricingGroup = new PricingGroup();
        $this->assertInstanceOf('iMoneza\Data\Resource', $resource->setPricingGroup($pricingGroup));
        $this->assertEquals($pricingGroup, $resource->getPricingGroup());
    }

    public function testPricingModel()
    {
        $resource = new Resource();
        $this->assertNull($resource->getPricingModel());
        $this->assertInstanceOf('iMoneza\Data\Resource', $resource->setPricingModel('some model'));
        $this->assertEquals('some model', $resource->getPricingModel());
    }

    public function testPrice()
    {
        $resource = new Resource();
        $this->assertNull($resource->getPrice());
        $this->assertInstanceOf('iMoneza\Data\Resource', $resource->setPrice(13.37));
        $this->assertEquals(13.37, $resource->getPrice());
    }

    public function testExpirationPeriodUnit()
    {
        $resource = new Resource();
        $this->assertNull($resource->getExpirationPeriodUnit());
        $this->assertInstanceOf('iMoneza\Data\Resource', $resource->setExpirationPeriodUnit('unit!'));
        $this->assertEquals('unit!', $resource->getExpirationPeriodUnit());
    }

    public function testExpirationPeriodValue()
    {
        $resource = new Resource();
        $this->assertNull($resource->getExpirationPeriodValue());
        $this->assertInstanceOf('iMoneza\Data\Resource', $resource->setExpirationPeriodValue(12));
        $this->assertEquals(12, $resource->getExpirationPeriodValue());
    }

    public function testTargetConversionRate()
    {
        $resource = new Resource();
        $this->assertNull($resource->getTargetConversionRate());
        $this->assertInstanceOf('iMoneza\Data\Resource', $resource->setTargetConversionRate(12.12));
        $this->assertEquals(12.12, $resource->getTargetConversionRate());
    }

    public function testTargetConversionHitsPerRecalculationPeriod()
    {
        $resource = new Resource();
        $this->assertNull($resource->getTargetConversionHitsPerRecalculationPeriod());
        $this->assertInstanceOf('iMoneza\Data\Resource', $resource->setTargetConversionHitsPerRecalculationPeriod(1111));
        $this->assertEquals(1111, $resource->getTargetConversionHitsPerRecalculationPeriod());
    }

    public function testTargetFloor()
    {
        $resource = new Resource();
        $this->assertNull($resource->getTargetConversionPriceFloor());
        $this->assertInstanceOf('iMoneza\Data\Resource', $resource->setTargetConversionPriceFloor(3.71));
        $this->assertEquals(3.71, $resource->getTargetConversionPriceFloor());
    }

    public function testResourcePricingTiers()
    {
        $resource = new Resource();
        $this->assertNull($resource->getResourcePricingTiers());
        $this->assertInstanceOf('iMoneza\Data\Resource', $resource->setResourcePricingTiers(['something-here']));
        $this->assertEquals(['something-here'], $resource->getResourcePricingTiers());
    }

    public function testPaywallDescription()
    {
        $resource = new Resource();
        $this->assertNull($resource->getPaywallDescription());
        $this->assertInstanceOf('iMoneza\Data\Resource', $resource->setPaywallDescription("my description"));
        $this->assertEquals("my description", $resource->getPaywallDescription());
    }
    public function testPaywallShortDescription()
    {
        $resource = new Resource();
        $this->assertNull($resource->getPaywallShortDescription());
        $this->assertInstanceOf('iMoneza\Data\Resource', $resource->setPaywallShortDescription("short desc"));
        $this->assertEquals('short desc', $resource->getPaywallShortDescription());
    }
    
    public function testProperty()
    {
        $resource = new Resource();
        $this->assertNull($resource->getProperty());
        $property = new Property();
        $this->assertInstanceOf('iMoneza\Data\Resource', $resource->setProperty($property));
        $this->assertEquals($property, $resource->getProperty());
    }
}
