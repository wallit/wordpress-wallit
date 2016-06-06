<?php
/**
 * test callback result
 *
 * @author Aaron Saray
 */

namespace iMoneza\UnitTest\Options\Management;
use iMoneza\Options\Management\CallbackResult;


/**
 * Class CallbackResultTest
 * @package iMoneza\Tests\Options\Management
 */
class CallbackResultTest extends \PHPUnit_Framework_TestCase
{
    public function testSetCallbackToken()
    {
        $options = new CallbackResult();
        $this->assertInstanceOf('iMoneza\Options\Management\CallbackResult', $options->setCallbackToken('callback-token'));
        $this->assertAttributeEquals('callback-token', 'callbackToken', $options);
    }
    
    public function testGetRequestType()
    {
        $options = new CallbackResult();
        $this->assertEquals('get', $options->getRequestType());
    }

    public function testGetEndPoint()
    {
        $options = new CallbackResult();
        $options->setAccessKey('access-key');
        $options->setCallbackToken('my-token');
        $this->assertEquals('/api/Property/access-key/CallbackResult/my-token', $options->getEndPoint());
    }

    public function testGetDataObject()
    {
        $options = new CallbackResult();
        $this->assertInstanceOf('iMoneza\Data\CallbackResult', $options->getDataObject());
    }
}