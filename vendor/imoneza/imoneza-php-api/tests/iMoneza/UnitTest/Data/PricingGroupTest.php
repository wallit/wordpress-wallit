<?php
/**
 * Test
 *
 * @author Aaron Saray
 */

namespace iMoneza\UnitTest\Data;
use iMoneza\Data\PricingGroup;


/**
 * Class PricingGroupTest
 * @package iMoneza\UnitTest\Data
 */
class PricingGroupTest extends \PHPUnit_Framework_TestCase
{
    public function testPricingGroupID()
    {
        $pricingGroup = new PricingGroup();
        $this->assertNull($pricingGroup->getPricingGroupID());
        $this->assertInstanceOf('iMoneza\Data\PricingGroup', $pricingGroup->setPricingGroupID(24));
        $this->assertEquals(24, $pricingGroup->getPricingGroupID());
    }

    public function testName()
    {
        $pricingGroup = new PricingGroup();
        $this->assertNull($pricingGroup->getName());
        $this->assertInstanceOf('iMoneza\Data\PricingGroup', $pricingGroup->setName('name'));
        $this->assertEquals('name', $pricingGroup->getName());
    }

    public function testDefault()
    {
        $pricingGroup = new PricingGroup();
        $this->assertNull($pricingGroup->isDefault());
        $this->assertInstanceOf('iMoneza\Data\PricingGroup', $pricingGroup->setIsDefault(true));
        $this->assertTrue($pricingGroup->isDefault());
    }

    public function testPricingModel()
    {
        $pricingGroup = new PricingGroup();
        $this->assertNull($pricingGroup->getPricingModel());
        $this->assertInstanceOf('iMoneza\Data\PricingGroup', $pricingGroup->setPricingModel('model'));
        $this->assertEquals('model', $pricingGroup->getPricingModel());
    }

    public function testPrice()
    {
        $pricingGroup = new PricingGroup();
        $this->assertNull($pricingGroup->getPrice());
        $this->assertInstanceOf('iMoneza\Data\PricingGroup', $pricingGroup->setPrice(24.44));
        $this->assertEquals(24.44, $pricingGroup->getPrice());
    }

    public function testExpirationPeriodUnit()
    {
        $pricingGroup = new PricingGroup();
        $this->assertNull($pricingGroup->getExpirationPeriodUnit());
        $this->assertInstanceOf('iMoneza\Data\PricingGroup', $pricingGroup->setExpirationPeriodUnit('unit'));
        $this->assertEquals('unit', $pricingGroup->getExpirationPeriodUnit());
    }

    public function testExpirationPeriodValue()
    {
        $pricingGroup = new PricingGroup();
        $this->assertNull($pricingGroup->getExpirationPeriodValue());
        $this->assertInstanceOf('iMoneza\Data\PricingGroup', $pricingGroup->setExpirationPeriodValue(99));
        $this->assertEquals(99, $pricingGroup->getExpirationPeriodValue());
    }

    public function testTargetConversionRate()
    {
        $pricingGroup = new PricingGroup();
        $this->assertNull($pricingGroup->getTargetConversionRate());
        $this->assertInstanceOf('iMoneza\Data\PricingGroup', $pricingGroup->setTargetConversionRate(12.34));
        $this->assertEquals(12.34, $pricingGroup->getTargetConversionRate());
    }

    public function testTargetConversionPriceFloor()
    {
        $pricingGroup = new PricingGroup();
        $this->assertNull($pricingGroup->getTargetConversionPriceFloor());
        $this->assertInstanceOf('iMoneza\Data\PricingGroup', $pricingGroup->setTargetConversionPriceFloor(1.99));
        $this->assertEquals(1.99, $pricingGroup->getTargetConversionPriceFloor());
    }

    public function testTargetConversionHitsPerRecalculationPeriod()
    {
        $pricingGroup = new PricingGroup();
        $this->assertNull($pricingGroup->getTargetConversionHitsPerRecalculationPeriod());
        $this->assertInstanceOf('iMoneza\Data\PricingGroup', $pricingGroup->setTargetConversionHitsPerRecalculationPeriod(8877));
        $this->assertEquals(8877, $pricingGroup->getTargetConversionHitsPerRecalculationPeriod());
    }
}
