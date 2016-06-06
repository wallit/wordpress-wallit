<?php
/**
 * This particular resource
 *
 * @author Aaron Saray
 */

namespace iMoneza\Data;

/**
 * Class Resource
 * @package iMoneza\Data
 */
class Resource extends DataAbstract
{
    /**
     * @var string
     */
    const PRICING_MODEL_INHERIT = 'Inherit';

    /**
     * @var string
     */
    const EXPIRATION_PERIOD_UNIT_NEVER = 'Never';

    /**
     * @var array items to convert to classes
     */
    protected $classKeys = ['Property', 'PricingGroup'];

    /**
     * @var array date times
     */
    protected $dateTimeKeys = ['PublicationDate'];

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $externalKey;

    /**
     * @var boolean
     */
    protected $active;

    /**
     * @var string
     */
    protected $URL;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $byline;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var \DateTime
     */
    protected $PublicationDate;

    /**
     * @var PricingGroup
     */
    protected $PricingGroup;

    /**
     * @var string
     */
    protected $pricingModel;

    /**
     * @var float
     */
    protected $price;

    /**
     * @var string
     */
    protected $expirationPeriodUnit;

    /**
     * @var int
     */
    protected $expirationPeriodValue;

    /**
     * @var float
     */
    protected $targetConversionRate;

    /**
     * @var float
     */
    protected $targetConversionPriceFloor;

    /**
     * @var int
     */
    protected $targetConversionHitsPerRecalculationPeriod;

    /**
     * @var array
     */
    protected $resourcePricingTiers;

    /**
     * @var string
     */
    protected $paywallDescription;

    /**
     * @var string
     */
    protected $paywallShortDescription;

    /**
     * @var Property
     */
    protected $Property;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Resource
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getExternalKey()
    {
        return $this->externalKey;
    }

    /**
     * @param string $externalKey
     * @return Resource
     */
    public function setExternalKey($externalKey)
    {
        $this->externalKey = $externalKey;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param boolean $active
     * @return Resource
     */
    public function setActive($active)
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @return string
     */
    public function getURL()
    {
        return $this->URL;
    }

    /**
     * @param string $URL
     * @return Resource
     */
    public function setURL($URL)
    {
        $this->URL = $URL;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Resource
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Resource
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getByline()
    {
        return $this->byline;
    }

    /**
     * @param string $byline
     * @return Resource
     */
    public function setByline($byline)
    {
        $this->byline = $byline;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getPublicationDate()
    {
        return $this->PublicationDate;
    }

    /**
     * @param \DateTime $PublicationDate
     * @return Resource
     */
    public function setPublicationDate(\DateTime $PublicationDate)
    {
        $this->PublicationDate = $PublicationDate;
        return $this;
    }

    /**
     * @return PricingGroup
     */
    public function getPricingGroup()
    {
        return $this->PricingGroup;
    }

    /**
     * @param PricingGroup $PricingGroup
     * @return Resource
     */
    public function setPricingGroup(PricingGroup $PricingGroup)
    {
        $this->PricingGroup = $PricingGroup;
        return $this;
    }

    /**
     * @return string
     */
    public function getPricingModel()
    {
        return $this->pricingModel;
    }

    /**
     * @param string $pricingModel
     * @return Resource
     */
    public function setPricingModel($pricingModel)
    {
        $this->pricingModel = $pricingModel;
        return $this;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     * @return Resource
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return string
     */
    public function getExpirationPeriodUnit()
    {
        return $this->expirationPeriodUnit;
    }

    /**
     * @param string $expirationPeriodUnit
     * @return Resource
     */
    public function setExpirationPeriodUnit($expirationPeriodUnit)
    {
        $this->expirationPeriodUnit = $expirationPeriodUnit;
        return $this;
    }

    /**
     * @return int
     */
    public function getExpirationPeriodValue()
    {
        return $this->expirationPeriodValue;
    }

    /**
     * @param int $expirationPeriodValue
     * @return Resource
     */
    public function setExpirationPeriodValue($expirationPeriodValue)
    {
        $this->expirationPeriodValue = $expirationPeriodValue;
        return $this;
    }

    /**
     * @return float
     */
    public function getTargetConversionRate()
    {
        return $this->targetConversionRate;
    }

    /**
     * @param float $targetConversionRate
     * @return Resource
     */
    public function setTargetConversionRate($targetConversionRate)
    {
        $this->targetConversionRate = $targetConversionRate;
        return $this;
    }

    /**
     * @return float
     */
    public function getTargetConversionPriceFloor()
    {
        return $this->targetConversionPriceFloor;
    }

    /**
     * @param float $targetConversionPriceFloor
     * @return Resource
     */
    public function setTargetConversionPriceFloor($targetConversionPriceFloor)
    {
        $this->targetConversionPriceFloor = $targetConversionPriceFloor;
        return $this;
    }

    /**
     * @return int
     */
    public function getTargetConversionHitsPerRecalculationPeriod()
    {
        return $this->targetConversionHitsPerRecalculationPeriod;
    }

    /**
     * @param int $targetConversionHitsPerRecalculationPeriod
     * @return Resource
     */
    public function setTargetConversionHitsPerRecalculationPeriod($targetConversionHitsPerRecalculationPeriod)
    {
        $this->targetConversionHitsPerRecalculationPeriod = $targetConversionHitsPerRecalculationPeriod;
        return $this;
    }

    /**
     * @return array
     */
    public function getResourcePricingTiers()
    {
        return $this->resourcePricingTiers;
    }

    /**
     * @param array $resourcePricingTiers
     * @return Resource
     */
    public function setResourcePricingTiers($resourcePricingTiers)
    {
        $this->resourcePricingTiers = $resourcePricingTiers;
        return $this;
    }

    /**
     * @return string
     */
    public function getPaywallDescription()
    {
        return $this->paywallDescription;
    }

    /**
     * @param string $paywallDescription
     * @return Resource
     */
    public function setPaywallDescription($paywallDescription)
    {
        $this->paywallDescription = $paywallDescription;
        return $this;
    }

    /**
     * @return string
     */
    public function getPaywallShortDescription()
    {
        return $this->paywallShortDescription;
    }

    /**
     * @param string $paywallShortDescription
     * @return Resource
     */
    public function setPaywallShortDescription($paywallShortDescription)
    {
        $this->paywallShortDescription = $paywallShortDescription;
        return $this;
    }
    
    /**
     * @return Property
     */
    public function getProperty()
    {
        return $this->Property;
    }

    /**
     * @param Property $Property
     * @return Resource
     */
    public function setProperty($Property)
    {
        $this->Property = $Property;
        return $this;
    }
}
