<?php
/**
 * test
 *
 * @author Aaron Saray
 */

namespace iMoneza\UnitTest\Options\Management;
use iMoneza\Options\Management\GetResourcesFromProperty;


/**
 * Class GetResourcesFromPropertyTest
 * @package iMoneza\Tests\Options\Management
 */
class GetResourcesFromPropertyTest extends \PHPUnit_Framework_TestCase
{
    public function testGetRequestType()
    {
        $options = new GetResourcesFromProperty();
        $this->assertEquals('get', $options->getRequestType());
    }

    public function testGetEndPoint()
    {
        $options = new GetResourcesFromProperty();
        $options->setAccessKey('access-key');
        $this->assertEquals('/api/Property/access-key/Resource', $options->getEndPoint());
    }

    public function testGetDataObject()
    {
        $options = new GetResourcesFromProperty();
        $this->assertInstanceOf('iMoneza\Data\ResourceCollection', $options->getDataObject());
    }
}