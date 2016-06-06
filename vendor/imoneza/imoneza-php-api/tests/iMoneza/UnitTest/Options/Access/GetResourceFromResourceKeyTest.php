<?php
/**
 * test
 *
 * @author Aaron Saray
 */

namespace iMoneza\UnitTest\Options\Access;

use iMoneza\Options\Access\GetResourceFromResourceKey;

/**
 * Class ResourceFromResourceKeyTest
 * @package iMoneza\Tests\Options\Access
 */
class ResourceFromResourceKeyTest extends \PHPUnit_Framework_TestCase
{
    public function testSetResourceKey()
    {
        $options = new GetResourceFromResourceKey();
        $this->assertInstanceOf('iMoneza\Options\Access\GetResourceFromResourceKey', $options->setResourceKey('resource-key'));
        $this->assertAttributeEquals('resource-key', 'resourceKey', $options);
    }

    public function testSetResourceURL()
    {
        $options = new GetResourceFromResourceKey();
        $this->assertInstanceOf('iMoneza\Options\Access\GetResourceFromResourceKey', $options->setResourceURL('resource-url'));
        $this->assertAttributeEquals('resource-url', 'ResourceURL', $options);
    }

    public function testSetUserToken()
    {
        $options = new GetResourceFromResourceKey();
        $this->assertInstanceOf('iMoneza\Options\Access\GetResourceFromResourceKey', $options->setUserToken('user-token'));
        $this->assertAttributeEquals('user-token', 'UserToken', $options);
    }

    public function testSetIpAddress()
    {
        $options = new GetResourceFromResourceKey();
        $this->assertInstanceOf('iMoneza\Options\Access\GetResourceFromResourceKey', $options->setIP('123.123.123.123'));
        $this->assertAttributeEquals('123.123.123.123', 'IP', $options);
    }

    public function testSetAdBlockerStatus()
    {
        $options = new GetResourceFromResourceKey();
        $this->assertInstanceOf('iMoneza\Options\Access\GetResourceFromResourceKey', $options->setAdBlockerStatus(GetResourceFromResourceKey::AD_BLOCKER_STATUS_DETECTED));
        $this->assertAttributeEquals(GetResourceFromResourceKey::AD_BLOCKER_STATUS_DETECTED, 'AdBlockerStatus', $options);
    }
    
    public function testGetRequestType()
    {
        $options = new GetResourceFromResourceKey();
        $this->assertEquals('get', $options->getRequestType());
    }

    public function testGetEndPoint()
    {
        $options = new GetResourceFromResourceKey();
        $options->setAccessKey('access-key')->setResourceKey('resource-key');
        $this->assertEquals('/api/Resource/access-key/resource-key', $options->getEndPoint());
    }

    public function testGetDataObject()
    {
        $options = new GetResourceFromResourceKey();
        $this->assertInstanceOf('iMoneza\Data\ResourceAccess', $options->getDataObject());
    }
}