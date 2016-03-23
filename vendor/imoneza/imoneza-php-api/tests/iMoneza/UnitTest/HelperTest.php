<?php
/**
 * Unit Test
 *
 * @author Aaron Saray
 */

namespace iMoneza\UnitTest;

use iMoneza\Helper;

/**
 * Class HelperTest
 * @package iMoneza\UnitTest
 */
class HelperTest extends \PHPUnit_Framework_TestCase
{
    public function testRetrieveIP()
    {
        $this->assertEmpty(Helper::getCurrentIP());

        putenv('REMOTE_ADDR=8.8.8.8');
        $this->assertEquals('8.8.8.8', Helper::getCurrentIP());

        putenv('REMOTE_ADDR=');
        putenv('HTTP_CLIENT_IP=8.8.4.4, 8.8.8.8');
        $this->assertEquals('8.8.4.4', Helper::getCurrentIP());

        putenv('REMOTE_ADDR=');
        putenv('HTTP_CLIENT_IP=127.0.0.1');
        $this->assertEmpty(Helper::getCurrentIP());
    }
}