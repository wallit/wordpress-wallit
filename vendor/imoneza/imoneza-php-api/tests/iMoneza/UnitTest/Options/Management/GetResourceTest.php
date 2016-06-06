<?php
/**
 * test
 *
 * @author Aaron Saray
 */

namespace iMoneza\UnitTest\Options\Management;
use iMoneza\Options\Management\GetResource;


/**
 * Class GetResourceTest
 * @package iMoneza\Tests\Options\Management
 */
class GetResourceTest extends \PHPUnit_Framework_TestCase
{
    public function testSetResourceKey()
    {
        $options = new GetResource();
        $this->assertInstanceOf('iMoneza\Options\Management\GetResource', $options->setResourceKey('resource-key'));
        $this->assertAttributeEquals('resource-key', 'resourceKey', $options);
    }

    public function testGetRequestType()
    {
        $options = new GetResource();
        $this->assertEquals('get', $options->getRequestType());
    }

    public function testGetEndPoint()
    {
        $options = new GetResource();
        $options->setAccessKey('access-key')->setResourceKey('resource-key');
        $this->assertEquals('/api/Property/access-key/Resource/resource-key', $options->getEndPoint());
    }

    public function testGetDataObject()
    {
        $options = new GetResource();
        $this->assertInstanceOf('iMoneza\Data\Resource', $options->getDataObject());
    }
}