<?php
/**
 * Test
 *
 * @author Aaron Saray
 */

namespace iMoneza\UnitTest\Data;
use iMoneza\Data\SubscriptionGroup;


/**
 * Class SubscriptionGroupTest
 * @package iMoneza\UnitTest\Data
 */
class SubscriptionGroupTest extends \PHPUnit_Framework_TestCase
{
    public function testSubscriptionGroupID()
    {
        $subscriptionGroup = new SubscriptionGroup();
        $this->assertNull($subscriptionGroup->getSubscriptionGroupID());
        $this->assertInstanceOf('iMoneza\Data\SubscriptionGroup', $subscriptionGroup->setSubscriptionGroupID(445));
        $this->assertEquals(445, $subscriptionGroup->getSubscriptionGroupID());
    }

    public function testName()
    {
        $subscriptionGroup = new SubscriptionGroup();
        $this->assertNull($subscriptionGroup->getName());
        $this->assertInstanceOf('iMoneza\Data\SubscriptionGroup', $subscriptionGroup->setName('mi nombre es'));
        $this->assertEquals('mi nombre es', $subscriptionGroup->getName());
    }

    public function testTitle()
    {
        $subscriptionGroup = new SubscriptionGroup();
        $this->assertNull($subscriptionGroup->getTitle());
        $this->assertInstanceOf('iMoneza\Data\SubscriptionGroup', $subscriptionGroup->setTitle('11 foreclosure'));
        $this->assertEquals('11 foreclosure', $subscriptionGroup->getTitle());
    }

    public function testPrice()
    {
        $subscriptionGroup = new SubscriptionGroup();
        $this->assertNull($subscriptionGroup->getPrice());
        $this->assertInstanceOf('iMoneza\Data\SubscriptionGroup', $subscriptionGroup->setPrice(23.12));
        $this->assertEquals(23.12, $subscriptionGroup->getPrice());
    }

    public function testPeriod()
    {
        $subscriptionGroup = new SubscriptionGroup();
        $this->assertNull($subscriptionGroup->getPeriod());
        $this->assertInstanceOf('iMoneza\Data\SubscriptionGroup', $subscriptionGroup->setPeriod('my blue one'));
        $this->assertEquals('my blue one', $subscriptionGroup->getPeriod());
    }

    public function testPaywallDescription()
    {
        $subscriptionGroup = new SubscriptionGroup();
        $this->assertNull($subscriptionGroup->getPaywallDescription());
        $this->assertInstanceOf('iMoneza\Data\SubscriptionGroup', $subscriptionGroup->setPaywallDescription('here ya go'));
        $this->assertEquals('here ya go', $subscriptionGroup->getPaywallDescription());
    }

    public function testPaywallShortDescription()
    {
        $subscriptionGroup = new SubscriptionGroup();
        $this->assertNull($subscriptionGroup->getPaywallShortDescription());
        $this->assertInstanceOf('iMoneza\Data\SubscriptionGroup', $subscriptionGroup->setPaywallShortDescription('desc'));
        $this->assertEquals('desc', $subscriptionGroup->getPaywallShortDescription());
    }
}
