<?php
/**
 * Test
 *
 * @author Aaron Saray
 */

namespace iMoneza\UnitTest\Data;
use iMoneza\Data\Subscription;


/**
 * Class SubscriptionTest
 * @package iMoneza\UnitTest\Data
 */
class SubscriptionTest extends \PHPUnit_Framework_TestCase
{
    public function testExpired()
    {
        $subscription = new Subscription();
        $this->assertNull($subscription->isExpired());
        $this->assertInstanceOf('iMoneza\Data\Subscription', $subscription->setIsExpired(true));
        $this->assertTrue($subscription->isExpired());
    }

    public function testExpirationDate()
    {
        $subscription = new Subscription();
        $this->assertNull($subscription->getExpirationDate());
        $time = new \DateTime();
        $this->assertInstanceOf('iMoneza\Data\Subscription', $subscription->setExpirationDate($time));
        $this->assertEquals($time, $subscription->getExpirationDate());
    }

    public function testCurrent()
    {
        $subscription = new Subscription();
        $this->assertNull($subscription->isCurrent());
        $this->assertInstanceOf('iMoneza\Data\Subscription', $subscription->setIsCurrent(false));
        $this->assertFalse($subscription->isCurrent());
    }

    public function testSubscriptionGroupID()
    {
        $subscription = new Subscription();
        $this->assertNull($subscription->getSubscriptionGroupID());
        $this->assertInstanceOf('iMoneza\Data\Subscription', $subscription->setSubscriptionGroupID(27));
        $this->assertEquals(27, $subscription->getSubscriptionGroupID());
    }
}
