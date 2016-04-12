<?php
/**
 * Test the configuration Trait
 *
 * @author Aaron Saray
 */

namespace iMoneza\UnitTest\Options;

use iMoneza\Options\OptionsAbstract;

/**
 * Class ConfigurationTraitTest
 * @package iMoneza\Tests\Options
 */
class ConfigurationTraitTest extends \PHPUnit_Framework_TestCase
{
    public function testBaseApiBaseURL()
    {
        /** @var \iMoneza\Options\ConfigurationTrait $stub */
        $stub = $this->getMockForTrait('iMoneza\Options\ConfigurationTrait');
        $stub->apiBaseUrl = '';


        $this->assertEmpty($stub->getApiBaseURL());
        $this->assertNotEmpty($stub->setApiBaseURL('base-url'));
        $this->assertEquals('base-url', $stub->getApiBaseURL());
    }

    public function testHasAccessKey()
    {
        /** @var \iMoneza\Options\ConfigurationTrait $stub */
        $stub = $this->getMockForTrait('iMoneza\Options\ConfigurationTrait');

        $this->assertFalse($stub->hasAccessKey());
        $stub->setAccessKey('asdf');
        $this->assertTrue($stub->hasAccessKey());
    }

}
