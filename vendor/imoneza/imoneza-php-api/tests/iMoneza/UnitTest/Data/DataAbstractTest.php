<?php
/**
 * Test
 *
 * @author Aaron Saray
 */

namespace iMoneza\UnitTest\Data;
use iMoneza\Data\Property;
use iMoneza\Data\Resource;


/**
 * Class DataAbstractTest
 * @package iMoneza\UnitTest\Data
 */
class DataAbstractTest extends \PHPUnit_Framework_TestCase
{
    public function testPopulateWithDateTime()
    {
        $values = ['Name'=>'the name', 'PublicationDate'=>'2014-01-01 00:00:00'];

        $resource = new Resource();
        $resource->populate($values);

        $this->assertInstanceOf('\DateTime', $resource->getPublicationDate());
        $this->assertEquals('the name', $resource->getName());
    }

    public function testPopulateWithClass()
    {
        $values = ['Name'=>'the name', 'Property'=>['Name'=>'property name']];

        $resource = new Resource();
        $resource->populate($values);

        $this->assertInstanceOf('iMoneza\Data\Property', $resource->getProperty());
        $this->assertEquals('property name', $resource->getProperty()->getName());
        $this->assertEquals('the name', $resource->getName());
    }

    public function testPopulateWithArrayClass()
    {
        $values = ['Name'=>'the name', 'PricingGroups'=>[['Name'=>'pricing group name']]];

        $property = new Property();
        $property->populate($values);

        $this->assertTrue(is_array($property->getPricingGroups()));
        $this->assertEquals('pricing group name', $property->getPricingGroups()[0]->getName());
        $this->assertEquals('the name', $property->getName());
    }

    public function testPopulateWithPropertyNotAvailableSilentlyContinues()
    {
        $property = new Property();
        $property->populate(['nothing-here'=>'no value']);
        $this->addToAssertionCount(1); // no error here
    }
}
