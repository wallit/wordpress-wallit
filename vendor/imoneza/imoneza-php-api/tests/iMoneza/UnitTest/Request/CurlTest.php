<?php
/**
 * Unit test
 *
 * @author Aaron Saray
 */

namespace iMoneza\UnitTest\Request;
use iMoneza\Request\Curl;

/**
 * Class CurlTest
 * @package iMoneza\UnitTest\Request
 */
class CurlTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructorOptions()
    {
        $stub = $this->getMockBuilder('iMoneza\Request\Curl')
            ->disableOriginalConstructor()
            ->setMethods(['setOption'])
            ->getMock();

        $stub->method('setOption')
            ->will($this->returnCallback(function($option, $value) {
                \PHPUnit_Framework_Assert::assertEquals(CURLOPT_RETURNTRANSFER, $option);
                \PHPUnit_Framework_Assert::assertTrue($value);
            }));

        $stub->__construct();
    }

    public function testSetUrl()
    {
        $stub = $this->getMockBuilder('iMoneza\Request\Curl')
            ->setMethods(['setOption'])
            ->getMock();

        $stub->method('setOption')
            ->will($this->returnCallback(function($option, $value) {
                \PHPUnit_Framework_Assert::assertEquals(CURLOPT_URL, $option);
                \PHPUnit_Framework_Assert::assertEquals('http://google.com', $value);
            }));

        $stub->setUrl('http://google.com');
    }

    public function testSetAuthentication()
    {
        $stub = $this->getMockBuilder('iMoneza\Request\Curl')
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();

        $this->assertInstanceOf('iMoneza\Request\Curl', $stub->setAuthentication('auth-value'));
        $this->assertAttributeEquals('auth-value', 'authentication', $stub);
    }

    public function testSetTimestamp()
    {
        $stub = $this->getMockBuilder('iMoneza\Request\Curl')
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();

        $this->assertInstanceOf('iMoneza\Request\Curl', $stub->setTimestamp('2015-01-01'));
        $this->assertAttributeEquals('2015-01-01', 'timestamp', $stub);
    }

    public function testSetRequestTypeAndPayloadIsGet()
    {
        $stub = $this->getMockBuilder('iMoneza\Request\Curl')
            ->disableOriginalConstructor()
            ->setMethods(['setOption'])
            ->getMock();
        $stub->expects($this->never())->method('setOption');
        $this->assertInstanceOf('iMoneza\Request\Curl', $stub->setRequestTypeAndPayload('get'));
    }

    public function testSetRequestTypeAndPayloadPostPayloadBlank()
    {
        $stub = $this->getMockBuilder('iMoneza\Request\Curl')
            ->disableOriginalConstructor()
            ->setMethods(['setOption'])
            ->getMock();
        $stub->method('setOption')
            ->will($this->returnCallback(function($option, $value) {
                \PHPUnit_Framework_Assert::assertEquals(CURLOPT_POST, $option);
                \PHPUnit_Framework_Assert::assertEquals(true, $value);
            }));
        $this->assertInstanceOf('iMoneza\Request\Curl', $stub->setRequestTypeAndPayload('post'));
    }

    public function testSetRequestTypeAndPayloadPostPayloadNotEmpty()
    {
        $stub = $this->getMockBuilder('iMoneza\Request\Curl')
            ->disableOriginalConstructor()
            ->setMethods(['setOption'])
            ->getMock();
        $stub->expects($this->at(0))->method('setOption')
            ->will($this->returnCallback(function($option, $value) {
                \PHPUnit_Framework_Assert::assertEquals(CURLOPT_POSTFIELDS, $option);
                \PHPUnit_Framework_Assert::assertEquals('my payload', $value);
            }));
        $stub->expects($this->at(1))->method('setOption')
            ->will($this->returnCallback(function($option, $value) {
                \PHPUnit_Framework_Assert::assertEquals(CURLOPT_POST, $option);
                \PHPUnit_Framework_Assert::assertEquals(true, $value);
            }));
        $this->assertInstanceOf('iMoneza\Request\Curl', $stub->setRequestTypeAndPayload('post', 'my payload'));
    }

    public function testSetRequestTypeAndPayloadPutPayloadNotEmpty()
    {
        $stub = $this->getMockBuilder('iMoneza\Request\Curl')
            ->disableOriginalConstructor()
            ->setMethods(['setOption'])
            ->getMock();
        $stub->expects($this->at(0))->method('setOption')
            ->will($this->returnCallback(function($option, $value) {
                \PHPUnit_Framework_Assert::assertEquals(CURLOPT_POSTFIELDS, $option);
                \PHPUnit_Framework_Assert::assertEquals('my payload', $value);
            }));
        $stub->expects($this->at(1))->method('setOption')
            ->will($this->returnCallback(function($option, $value) {
                \PHPUnit_Framework_Assert::assertEquals(CURLOPT_CUSTOMREQUEST, $option);
                \PHPUnit_Framework_Assert::assertEquals('PUT', $value);
            }));
        $this->assertInstanceOf('iMoneza\Request\Curl', $stub->setRequestTypeAndPayload('put', 'my payload'));
    }
}