<?php
/**
 * Test
 *
 * @author Aaron Saray
 */

namespace iMoneza\UnitTest\Data;
use iMoneza\Data\Purchase;


/**
 * Class PurchaseTest
 * @package iMoneza\UnitTest\Data
 */
class PurchaseTest extends \PHPUnit_Framework_TestCase
{
    public function testPurchased()
    {
        $property = new Purchase();
        $this->assertNull($property->isPurchased());
        $this->assertInstanceOf('iMoneza\Data\Purchase', $property->setIsPurchased(true));
        $this->assertTrue($property->isPurchased());
    }
}
