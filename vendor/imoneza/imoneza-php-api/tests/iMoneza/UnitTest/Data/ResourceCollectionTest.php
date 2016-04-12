<?php
/**
 * Test
 *
 * @author Aaron Saray
 */

namespace iMoneza\UnitTest\Data;
use iMoneza\Data\ResourceCollection;


/**
 * Class ResourceCollectionTest
 * @package iMoneza\UnitTest\Data
 */
class ResourceCollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testWholeThing()
    {
        $names = [
            ['name'=>'one'],
            ['name'=>'two'],
            ['name'=>'three']
        ];

        $collection = new ResourceCollection();
        $this->assertInstanceOf('iMoneza\Data\ResourceCollection', $collection->populate($names));

        $this->assertEquals('one', $collection->current()->getName());
        $collection->next();
        $this->assertEquals('two', $collection->current()->getName());
        $collection->next();
        $this->assertEquals('three', $collection->current()->getName());

        $this->assertEquals(2, $collection->key());
        $collection->rewind();
        $this->assertEquals(0, $collection->key());
        $collection->next();
        $collection->next();
        $collection->next();
        $collection->next();
        $this->assertFalse($collection->valid());
    }
}
