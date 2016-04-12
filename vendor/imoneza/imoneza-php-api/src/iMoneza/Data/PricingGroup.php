<?php
/**
 * Pricing Group object
 *
 * @author Aaron Saray
 */

namespace iMoneza\Data;

/**
 * Class PricingGroup
 * @package iMoneza\Data
 */
class PricingGroup extends DataAbstract
{
    /**
     * @var string the period of never
     */
    const EXPIRATION_PERIOD_UNIT_NEVER = 'Never';

    /**
     * @var string pricing model of variable
     */
    const PRICING_MODEL_VARIABLE = 'VariablePrice';

    /**
     * @var string
     */
    protected $PricingGroupID;

    /**
     * @var string
     */
    protected $Name;

    /**
     * @var boolean
     */
    protected $IsDefault;

    /**
     * @var string
     */
    protected $PricingModel;

    /**
     * @var float
     */
    protected $Price;

    /**
     * @var string
     */
    protected $ExpirationPeriodUnit;

    /**
     * @var int
     */
    protected $ExpirationPeriodValue;

    /**
     * @var float
     */
    protected $TargetConversionRate;

    /**
     * @var float
     */
    protected $TargetConversionPriceFloor;

    /**
     * @var int
     */
    protected $TargetConversionHitsPerRecalculationPeriod;

    /**
     * @return string
     */
    public function getPricingGroupID()
    {
        return $this->PricingGroupID;
    }

    /**
     * @param string $PricingGroupID
     * @return PricingGroup
     */
    public function setPricingGroupID($PricingGroupID)
    {
        $this->PricingGroupID = $PricingGroupID;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->Name;
    }

    /**
     * @param string $Name
     * @return PricingGroup
     */
    public function setName($Name)
    {
        $this->Name = $Name;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isDefault()
    {
        return $this->IsDefault;
    }

    /**
     * @param boolean $IsDefault
     * @return PricingGroup
     */
    public function setIsDefault($IsDefault)
    {
        $this->IsDefault = $IsDefault;
        return $this;
    }

    /**
     * @return string
     */
    public function getPricingModel()
    {
        return $this->PricingModel;
    }

    /**
     * @param string $PricingModel
     * @return PricingGroup
     */
    public function setPricingModel($PricingModel)
    {
        $this->PricingModel = $PricingModel;
        return $this;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->Price;
    }

    /**
     * @param float $Price
     * @return PricingGroup
     */
    public function setPrice($Price)
    {
        $this->Price = $Price;
        return $this;
    }

    /**
     * @return string
     */
    public function getExpirationPeriodUnit()
    {
        return $this->ExpirationPeriodUnit;
    }

    /**
     * @param string $ExpirationPeriodUnit
     * @return PricingGroup
     */
    public function setExpirationPeriodUnit($ExpirationPeriodUnit)
    {
        $this->ExpirationPeriodUnit = $ExpirationPeriodUnit;
        return $this;
    }

    /**
     * @return int
     */
    public function getExpirationPeriodValue()
    {
        return $this->ExpirationPeriodValue;
    }

    /**
     * @param int $ExpirationPeriodValue
     * @return PricingGroup
     */
    public function setExpirationPeriodValue($ExpirationPeriodValue)
    {
        $this->ExpirationPeriodValue = $ExpirationPeriodValue;
        return $this;
    }

    /**
     * @return float
     */
    public function getTargetConversionRate()
    {
        return $this->TargetConversionRate;
    }

    /**
     * @param float $TargetConversionRate
     * @return PricingGroup
     */
    public function setTargetConversionRate($TargetConversionRate)
    {
        $this->TargetConversionRate = $TargetConversionRate;
        return $this;
    }

    /**
     * @return float
     */
    public function getTargetConversionPriceFloor()
    {
        return $this->TargetConversionPriceFloor;
    }

    /**
     * @param float $TargetConversionPriceFloor
     * @return PricingGroup
     */
    public function setTargetConversionPriceFloor($TargetConversionPriceFloor)
    {
        $this->TargetConversionPriceFloor = $TargetConversionPriceFloor;
        return $this;
    }

    /**
     * @return int
     */
    public function getTargetConversionHitsPerRecalculationPeriod()
    {
        return $this->TargetConversionHitsPerRecalculationPeriod;
    }

    /**
     * @param int $TargetConversionHitsPerRecalculationPeriod
     * @return PricingGroup
     */
    public function setTargetConversionHitsPerRecalculationPeriod($TargetConversionHitsPerRecalculationPeriod)
    {
        $this->TargetConversionHitsPerRecalculationPeriod = $TargetConversionHitsPerRecalculationPeriod;
        return $this;
    }
}
