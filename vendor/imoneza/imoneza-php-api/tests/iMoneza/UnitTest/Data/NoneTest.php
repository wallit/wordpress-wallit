<?php
/**
 * Test
 *
 * @author Aaron Saray
 */

namespace iMoneza\UnitTest\Data;

use iMoneza\Data\None;

/**
 * Class NoneTest
 * @package iMoneza\UnitTest\Data
 */
class NoneTest extends \PHPUnit_Framework_TestCase
{
    public function testSetDoesNothing()
    {
        $noneClass = new None();
        $clonedNone = clone $noneClass;
        $this->assertInstanceOf('iMoneza\Data\None', $noneClass->setSomething('here'));
        $this->assertEquals($noneClass, $clonedNone);

        $this->assertInstanceOf('iMoneza\Data\None', $noneClass->populate(['here'=>'there']));
        $this->assertEquals($noneClass, $clonedNone);
    }
}
