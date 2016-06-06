<?php
/**
 * test
 *
 * @author Aaron Saray
 */

namespace iMoneza\UnitTest\Options\Access;

use iMoneza\Options\Access\GetResourceFromTemporaryUserToken;

/**
 * Class GetResourceFromTemporaryUserTokenTest
 * @package iMoneza\Tests\Options\Access
 */
class GetResourceFromTemporaryUserTokenTest extends \PHPUnit_Framework_TestCase
{
    public function testSetResourceKey()
    {
        $options = new GetResourceFromTemporaryUserToken();
        $this->assertInstanceOf('iMoneza\Options\Access\GetResourceFromTemporaryUserToken', $options->setResourceKey('resource-key'));
        $this->assertAttributeEquals('resource-key', 'ResourceKey', $options);
    }

    public function testSetResourceURL()
    {
        $options = new GetResourceFromTemporaryUserToken();
        $this->assertInstanceOf('iMoneza\Options\Access\GetResourceFromTemporaryUserToken', $options->setResourceURL('resource-url'));
        $this->assertAttributeEquals('resource-url', 'ResourceURL', $options);
    }

    public function testSetTemporaryUserToken()
    {
        $options = new GetResourceFromTemporaryUserToken();
        $this->assertInstanceOf('iMoneza\Options\Access\GetResourceFromTemporaryUserToken', $options->setTemporaryUserToken('temp-user-token'));
        $this->assertAttributeEquals('temp-user-token', 'temporaryUserToken', $options);
    }

    public function testSetIpAddress()
    {
        $options = new GetResourceFromTemporaryUserToken();
        $this->assertInstanceOf('iMoneza\Options\Access\GetResourceFromTemporaryUserToken', $options->setIP('123.123.123.123'));
        $this->assertAttributeEquals('123.123.123.123', 'IP', $options);
    }

    public function testSetAdBlockerStatus()
    {
        $options = new GetResourceFromTemporaryUserToken();
        $this->assertInstanceOf('iMoneza\Options\Access\GetResourceFromTemporaryUserToken', $options->setAdBlockerStatus(GetResourceFromTemporaryUserToken::AD_BLOCKER_STATUS_DETECTED));
        $this->assertAttributeEquals(GetResourceFromTemporaryUserToken::AD_BLOCKER_STATUS_DETECTED, 'AdBlockerStatus', $options);
    }
    
    public function testGetRequestType()
    {
        $options = new GetResourceFromTemporaryUserToken();
        $this->assertEquals('get', $options->getRequestType());
    }

    public function testGetEndPoint()
    {
        $options = new GetResourceFromTemporaryUserToken();
        $options->setAccessKey('access-key')->setTemporaryUserToken('temp-user-token');
        $this->assertEquals('/api/TemporaryUserToken/access-key/temp-user-token', $options->getEndPoint());
    }

    public function testGetDataObject()
    {
        $options = new GetResourceFromTemporaryUserToken();
        $this->assertInstanceOf('iMoneza\Data\ResourceAccess', $options->getDataObject());
    }
}