<?php
/**
 * test
 *
 * @author Aaron Saray
 */

namespace iMoneza\UnitTest\Options\Management;
use iMoneza\Options\Management\GetProperty;


/**
 * Class GetPropertyTest
 * @package iMoneza\Tests\Options\Management
 */
class GetPropertyTest extends \PHPUnit_Framework_TestCase
{
    public function testGetRequestType()
    {
        $options = new GetProperty();
        $this->assertEquals('get', $options->getRequestType());
    }

    public function testGetEndPoint()
    {
        $options = new GetProperty();
        $options->setAccessKey('access-key');
        $this->assertEquals('/api/Property/access-key', $options->getEndPoint());
    }

    public function testGetDataObject()
    {
        $options = new GetProperty();
        $this->assertInstanceOf('iMoneza\Data\Property', $options->getDataObject());
    }
}