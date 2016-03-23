<?php
/**
 * Test
 *
 * @author Aaron Saray
 */

namespace iMoneza\UnitTest\Data;
use iMoneza\Data\Property;


/**
 * Class PropertyTest
 * @package iMoneza\UnitTest\Data
 */
class PropertyTest extends \PHPUnit_Framework_TestCase
{
    public function testName()
    {
        $property = new Property();
        $this->assertNull($property->getName());
        $this->assertInstanceOf('iMoneza\Data\Property', $property->setName('the name'));
        $this->assertEquals('the name', $property->getName());
    }

    public function testTitle()
    {
        $property = new Property();
        $this->assertNull($property->getTitle());
        $this->assertInstanceOf('iMoneza\Data\Property', $property->setTitle('the title'));
        $this->assertEquals('the title', $property->getTitle());
    }

    public function testDynamicallyCreatedResource()
    {
        $property = new Property();
        $this->assertNull($property->isDynamicallyCreateResources());
        $this->assertInstanceOf('iMoneza\Data\Property', $property->setDynamicallyCreateResources(false));
        $this->assertFalse($property->isDynamicallyCreateResources());
    }

    public function testEnableQuota()
    {
        $property = new Property();
        $this->assertNull($property->isEnableQuota());
        $this->assertInstanceOf('iMoneza\Data\Property', $property->setEnableQuota(true));
        $this->assertTrue($property->isEnableQuota());
    }

    public function testEnableSubscriptions()
    {
        $property = new Property();
        $this->assertNull($property->isEnableSubscriptions());
        $this->assertInstanceOf('iMoneza\Data\Property', $property->setEnableSubscriptions(true));
        $this->assertTrue($property->isEnableSubscriptions());
    }

    public function testEnableSinglePurchases()
    {
        $property = new Property();
        $this->assertNull($property->isEnableSinglePurchases());
        $this->assertInstanceOf('iMoneza\Data\Property', $property->setEnableSinglePurchases(false));
        $this->assertFalse($property->isEnableSinglePurchases());
    }

    public function testFreeResourceRequiresAuthentication()
    {
        $property = new Property();
        $this->assertNull($property->isFreeResourcesRequireAuthentication());
        $this->assertInstanceOf('iMoneza\Data\Property', $property->setFreeResourcesRequireAuthentication(true));
        $this->assertTrue($property->isFreeResourcesRequireAuthentication());
    }

    public function testQuota()
    {
        $property = new Property();
        $this->assertNull($property->getQuota());
        $this->assertInstanceOf('iMoneza\Data\Property', $property->setQuota(5));
        $this->assertEquals(5, $property->getQuota());
    }

    public function testQuotaPeriod()
    {
        $property = new Property();
        $this->assertNull($property->getQuotaPeriod());
        $this->assertInstanceOf('iMoneza\Data\Property', $property->setQuotaPeriod('period'));
        $this->assertEquals('period', $property->getQuotaPeriod());
    }

    public function testSubscriptionGroups()
    {
        $property = new Property();
        $this->assertNull($property->getSubscriptionGroups());
        $this->assertInstanceOf('iMoneza\Data\Property', $property->setSubscriptionGroups(['something']));
        $this->assertEquals(['something'], $property->getSubscriptionGroups());
    }

    public function testPricingGroups()
    {
        $property = new Property();
        $this->assertNull($property->getPricingGroups());
        $this->assertInstanceOf('iMoneza\Data\Property', $property->setPricingGroups(['item here']));
        $this->assertEquals(['item here'], $property->getPricingGroups());
    }

    public function testAlwaysRequireAuthentication()
    {
        $property = new Property();
        $this->assertNull($property->isAlwaysRequireAuthentication());
        $this->assertInstanceOf('iMoneza\Data\Property', $property->setAlwaysRequireAuthentication(true));
        $this->assertTrue($property->isAlwaysRequireAuthentication());
    }
}
