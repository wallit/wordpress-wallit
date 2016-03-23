<?php
/**
 * Test
 *
 * @author Aaron Saray
 */

namespace iMoneza\UnitTest\Data;
use iMoneza\Data\Purchase;
use iMoneza\Data\Quota;
use iMoneza\Data\ResourceAccess;
use iMoneza\Data\Subscription;


/**
 * Class ResourceAccessTest
 * @package iMoneza\UnitTest\Data
 */
class ResourceAccessTest extends \PHPUnit_Framework_TestCase
{
    public function testUserToken()
    {
        $resourceAccess = new ResourceAccess();
        $this->assertNull($resourceAccess->getUserToken());
        $this->assertInstanceOf('iMoneza\Data\ResourceAccess', $resourceAccess->setUserToken('a-token-here'));
        $this->assertEquals('a-token-here', $resourceAccess->getUserToken());
    }

    public function testPropertyName()
    {
        $resourceAccess = new ResourceAccess();
        $this->assertNull($resourceAccess->getPropertyName());
        $this->assertInstanceOf('iMoneza\Data\ResourceAccess', $resourceAccess->setPropertyName('a property name'));
        $this->assertEquals('a property name', $resourceAccess->getPropertyName());
    }

    public function testPaywallDisplayStyle()
    {
        $resourceAccess = new ResourceAccess();
        $this->assertNull($resourceAccess->getPaywallDisplayStyle());
        $this->assertInstanceOf('iMoneza\Data\ResourceAccess', $resourceAccess->setPaywallDisplayStyle('cool'));
        $this->assertEquals('cool', $resourceAccess->getPaywallDisplayStyle());
    }

    public function testResourceName()
    {
        $resourceAccess = new ResourceAccess();
        $this->assertNull($resourceAccess->getResourceName());
        $this->assertInstanceOf('iMoneza\Data\ResourceAccess', $resourceAccess->setResourceName('resource name'));
        $this->assertEquals('resource name', $resourceAccess->getResourceName());
    }

    public function testUserName()
    {
        $resourceAccess = new ResourceAccess();
        $this->assertNull($resourceAccess->getUserName());
        $this->assertInstanceOf('iMoneza\Data\ResourceAccess', $resourceAccess->setUserName('quarter-pounder55'));
        $this->assertEquals('quarter-pounder55', $resourceAccess->getUserName());
    }

    public function testAnonymousUser()
    {
        $resourceAccess = new ResourceAccess();
        $this->assertNull($resourceAccess->isAnonymousUser());
        $this->assertInstanceOf('iMoneza\Data\ResourceAccess', $resourceAccess->setIsAnonymousUser(false));
        $this->assertFalse($resourceAccess->isAnonymousUser());
    }

    public function testQuota()
    {
        $resourceAccess = new ResourceAccess();
        $this->assertNull($resourceAccess->getQuota());
        $quota = new Quota();
        $this->assertInstanceOf('iMoneza\Data\ResourceAccess', $resourceAccess->setQuota($quota));
        $this->assertEquals($quota, $resourceAccess->getQuota());
    }

    public function testSubscription()
    {
        $resourceAccess = new ResourceAccess();
        $this->assertNull($resourceAccess->getSubscription());
        $subscription = new Subscription();
        $this->assertInstanceOf('iMoneza\Data\ResourceAccess', $resourceAccess->setSubscription($subscription));
        $this->assertEquals($subscription, $resourceAccess->getSubscription());
    }

    public function testPurchase()
    {
        $resourceAccess = new ResourceAccess();
        $this->assertNull($resourceAccess->getPurchase());
        $purchase = new Purchase();
        $this->assertInstanceOf('iMoneza\Data\ResourceAccess', $resourceAccess->setPurchase($purchase));
        $this->assertEquals($purchase, $resourceAccess->getPurchase());
    }

    public function testAccessAction()
    {
        $resourceAccess = new ResourceAccess();
        $this->assertNull($resourceAccess->getAccessAction());
        $this->assertInstanceOf('iMoneza\Data\ResourceAccess', $resourceAccess->setAccessAction('get-down'));
        $this->assertEquals('get-down', $resourceAccess->getAccessAction());
    }

    public function testAccessReason()
    {
        $resourceAccess = new ResourceAccess();
        $this->assertNull($resourceAccess->getAccessReason());
        $this->assertInstanceOf('iMoneza\Data\ResourceAccess', $resourceAccess->setAccessReason('nice'));
        $this->assertEquals('nice', $resourceAccess->getAccessReason());
    }

    public function testAccessActionUrl()
    {
        $resourceAccess = new ResourceAccess();
        $this->assertNull($resourceAccess->getAccessActionUrl());
        $this->assertInstanceOf('iMoneza\Data\ResourceAccess', $resourceAccess->setAccessActionUrl('http://google.com'));
        $this->assertEquals('http://google.com', $resourceAccess->getAccessActionUrl());
    }
}
