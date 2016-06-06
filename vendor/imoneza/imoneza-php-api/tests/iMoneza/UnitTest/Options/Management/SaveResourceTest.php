<?php
/**
 * test
 *
 * @author Aaron Saray
 */

namespace iMoneza\UnitTest\Options\Management;
use iMoneza\Options\Management\SaveResource;


/**
 * Class SaveResourceTest
 * @package iMoneza\Tests\Options\Management
 */
class SaveResourceTest extends \PHPUnit_Framework_TestCase
{
    public function testSetResourceKey()
    {
        $options = new SaveResource();
        $this->assertInstanceOf('iMoneza\Options\Management\SaveResource', $options->setPricingGroupId('12345'));
        $this->assertAttributeEquals(['PricingGroupID'=>12345], 'PricingGroup', $options);
    }

    public function testSetExternalKey()
    {
        $options = new SaveResource();
        $this->assertInstanceOf('iMoneza\Options\Management\SaveResource', $options->setExternalKey('external-key'));
        $this->assertAttributeEquals('external-key', 'ExternalKey', $options);
    }

    public function testSetName()
    {
        $options = new SaveResource();
        $this->assertInstanceOf('iMoneza\Options\Management\SaveResource', $options->setName('the-name'));
        $this->assertAttributeEquals('the-name', 'Name', $options);
    }

    public function testSetTitle()
    {
        $options = new SaveResource();
        $this->assertInstanceOf('iMoneza\Options\Management\SaveResource', $options->setTitle('title-here'));
        $this->assertAttributeEquals('title-here', 'Title', $options);
    }

    public function testGetRequestType()
    {
        $options = new SaveResource();
        $this->assertEquals('put', $options->getRequestType());
    }

    public function testGetEndPoint()
    {
        $options = new SaveResource();
        $options->setAccessKey('access-key')->setExternalKey('external-key');
        $this->assertEquals('/api/Property/access-key/Resource/external-key', $options->getEndPoint());
    }

    public function testGetDataObject()
    {
        $options = new SaveResource();
        $this->assertInstanceOf('iMoneza\Data\None', $options->getDataObject());
    }

    public function testSetActive()
    {
        $options = new SaveResource();
        $this->assertInstanceOf('iMoneza\Options\Management\SaveResource', $options->setActive(true));
        $this->assertAttributeEquals(true, 'Active', $options);
    }

    public function testSetURL()
    {
        $options = new SaveResource();
        $this->assertInstanceOf('iMoneza\Options\Management\SaveResource', $options->setURL('http://url.here'));
        $this->assertAttributeEquals('http://url.here', 'URL', $options);
    }

    public function testSetByline()
    {
        $options = new SaveResource();
        $this->assertInstanceOf('iMoneza\Options\Management\SaveResource', $options->setByline('by jose'));
        $this->assertAttributeEquals('by jose', 'Byline', $options);
    }

    public function testSetDescription()
    {
        $options = new SaveResource();
        $this->assertInstanceOf('iMoneza\Options\Management\SaveResource', $options->setDescription('description'));
        $this->assertAttributeEquals('description', 'Description', $options);
    }

    public function testSetPublicationDate()
    {
        $date = new \DateTime();
        $formatted = $date->format('c');
        $options = new SaveResource();
        $this->assertInstanceOf('iMoneza\Options\Management\SaveResource', $options->setPublicationDate($date));
        $this->assertAttributeEquals($formatted, 'PublicationDate', $options);
    }

    public function testSetPricingModel()
    {
        $options = new SaveResource();
        $this->assertInstanceOf('iMoneza\Options\Management\SaveResource', $options->setPricingModel('pricing-model'));
        $this->assertAttributeEquals('pricing-model', 'PricingModel', $options);
    }

    public function testSetPricingGroup()
    {
        $options = new SaveResource();
        $this->assertInstanceOf('iMoneza\Options\Management\SaveResource', $options->setPricingGroup([1,2,3]));
        $this->assertAttributeEquals([1,2,3], 'PricingGroup', $options);
    }

    public function testSetPrice()
    {
        $options = new SaveResource();
        $this->assertInstanceOf('iMoneza\Options\Management\SaveResource', $options->setPrice(12.34));
        $this->assertAttributeEquals(12.34, 'Price', $options);
    }

    public function testSetExpirationPeriodUnit()
    {
        $options = new SaveResource();
        $this->assertInstanceOf('iMoneza\Options\Management\SaveResource', $options->setExpirationPeriodUnit('p-unitttt!'));
        $this->assertAttributeEquals('p-unitttt!', 'ExpirationPeriodUnit', $options);
    }

    public function testSetExpirationPeriodValue()
    {
        $options = new SaveResource();
        $this->assertInstanceOf('iMoneza\Options\Management\SaveResource', $options->setExpirationPeriodValue(3));
        $this->assertAttributeEquals(3, 'ExpirationPeriodValue', $options);
    }

    public function testSetTargetConversionRate()
    {
        $options = new SaveResource();
        $this->assertInstanceOf('iMoneza\Options\Management\SaveResource', $options->setTargetConversionRate(12.12));
        $this->assertAttributeEquals(12.12, 'TargetConversionRate', $options);
    }

    public function testSetTargetConversionPriceFloor()
    {
        $options = new SaveResource();
        $this->assertInstanceOf('iMoneza\Options\Management\SaveResource', $options->setTargetConversionPriceFloor(0.02));
        $this->assertAttributeEquals(0.02, 'TargetConversionPriceFloor', $options);
    }

    public function testSetTargetConversionHitsPerRecalculationPeriod()
    {
        $options = new SaveResource();
        $this->assertInstanceOf('iMoneza\Options\Management\SaveResource', $options->setTargetConversionHitsPerRecalculationPeriod(400));
        $this->assertAttributeEquals(400, 'TargetConversionHitsPerRecalculationPeriod', $options);
    }

    public function testSetPaywallDescription()
    {
        $options = new SaveResource();
        $this->assertInstanceOf('iMoneza\Options\Management\SaveResource', $options->setPaywallDescription('paywall desc'));
        $this->assertAttributeEquals('paywall desc', 'PaywallDescription', $options);
    }

    public function testSetPaywallShortDescription()
    {
        $options = new SaveResource();
        $this->assertInstanceOf('iMoneza\Options\Management\SaveResource', $options->setPaywallShortDescription('here is something'));
        $this->assertAttributeEquals('here is something', 'PaywallShortDescription', $options);
    }
}