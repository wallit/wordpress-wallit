<?php
/**
 * Test
 *
 * @author Aaron Saray
 */

namespace iMoneza\UnitTest\Data;
use iMoneza\Data\CallbackResult;


/**
 * Class CallbackResultTest
 * @package iMoneza\UnitTest\Data
 */
class CallbackResultTest extends \PHPUnit_Framework_TestCase
{
    public function testData()
    {
        $callbackResult = new CallbackResult();
        $this->assertEmpty($callbackResult->getData());
        $this->assertInstanceOf('iMoneza\Data\CallbackResult', $callbackResult->setData(['ab', 'c']));
        $this->assertEquals(['ab', 'c'], $callbackResult->getData());
    }

    public function testPopulate()
    {
        $callbackResult = new CallbackResult();
        $this->assertEmpty($callbackResult->getData());
        $this->assertInstanceOf('iMoneza\Data\CallbackResult', $callbackResult->populate(['1', 2]));
        $this->assertEquals(['1', 2], $callbackResult->getData());
    }
}
