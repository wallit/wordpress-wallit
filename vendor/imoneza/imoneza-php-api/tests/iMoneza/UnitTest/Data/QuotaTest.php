<?php
/**
 * Test
 *
 * @author Aaron Saray
 */

namespace iMoneza\UnitTest\Data;
use iMoneza\Data\Quota;


/**
 * Class QuotaTest
 * @package iMoneza\UnitTest\Data
 */
class QuotaTest extends \PHPUnit_Framework_TestCase
{
    public function testEnabled()
    {
        $quota = new Quota();
        $this->assertNull($quota->isEnabled());
        $this->assertInstanceOf('iMoneza\Data\Quota', $quota->setIsEnabled(true));
        $this->assertTrue($quota->isEnabled());
    }

    public function testHitCount()
    {
        $quota = new Quota();
        $this->assertNull($quota->getHitCount());
        $this->assertInstanceOf('iMoneza\Data\Quota', $quota->setHitCount(44));
        $this->assertEquals(44, $quota->getHitCount());
    }

    public function testAllowedHits()
    {
        $quota = new Quota();
        $this->assertNull($quota->getAllowedHits());
        $this->assertInstanceOf('iMoneza\Data\Quota', $quota->setAllowedHits(24));
        $this->assertEquals(24, $quota->getAllowedHits());
    }

    public function testPeriodStartDate()
    {
        $quota = new Quota();
        $this->assertNull($quota->getPeriodStartDate());
        $time = new \DateTime();
        $this->assertInstanceOf('iMoneza\Data\Quota', $quota->setPeriodStartDate($time));
        $this->assertEquals($time, $quota->getPeriodStartDate());
    }

    public function testPeriodName()
    {
        $quota = new Quota();
        $this->assertNull($quota->getPeriodName());
        $this->assertInstanceOf('iMoneza\Data\Quota', $quota->setPeriodName('dot'));
        $this->assertEquals('dot', $quota->getPeriodName());
    }

    public function testIsMet()
    {
        $quota = new Quota();
        $this->assertNull($quota->isMet());
        $this->assertInstanceOf('iMoneza\Data\Quota', $quota->setIsMet(false));
        $this->assertFalse($quota->isMet());
    }
}
