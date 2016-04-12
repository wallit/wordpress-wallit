<?php
/**
 * Save (PUT) a resource
 *
 * @author Aaron Saray
 */

namespace iMoneza\Options\Management;

use iMoneza\Data\None;
use iMoneza\Options\ConfigurationTrait;
use iMoneza\Options\OptionsAbstract;

/**
 * Class SaveResource
 * @package iMoneza\Options\Management
 */
class SaveResource extends OptionsAbstract implements ManagementInterface
{
    use ConfigurationTrait, ManagementConfigurationTrait;

    /**
     * @var string the external key to use for this reference
     */
    protected $ExternalKey;

    /**
     * @var string the internal name
     */
    protected $Name;

    /**
     * @var string the title of the item
     */
    protected $Title;

    /**
     * @var array formatted pricing group property for this request
     */
    protected $PricingGroup;

    /**
     * @var boolean
     */
    protected $Active;

    /**
     * @var string
     */
    protected $URL;

    /**
     * @var string
     */
    protected $Byline;

    /**
     * @var string
     */
    protected $Description;

    /**
     * @var string
     */
    protected $PublicationDate;

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
     * @param $pricingGroupId
     * @return $this
     */
    public function setPricingGroupId($pricingGroupId)
    {
        $this->PricingGroup = ['PricingGroupID'=>$pricingGroupId];
        return $this;
    }

    /**
     * @param string $externalKey
     * @return $this
     */
    public function setExternalKey($externalKey)
    {
        $this->ExternalKey = $externalKey;
        return $this;
    }

    /**
     * @param string $name
     * @return SaveResource
     */
    public function setName($name)
    {
        $this->Name = $name;
        return $this;
    }

    /**
     * @param string $title
     * @return SaveResource
     */
    public function setTitle($title)
    {
        $this->Title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getEndPoint()
    {
        return "/api/Property/{$this->accessKey}/Resource/{$this->ExternalKey}";
    }

    /**
     * @return string
     */
    public function getRequestType()
    {
        return self::REQUEST_TYPE_PUT;
    }

    /**
     * @return None
     */
    public function getDataObject()
    {
        return new None();
    }

    /**
     * @param boolean $Active
     * @return SaveResource
     */
    public function setActive($Active)
    {
        $this->Active = $Active;
        return $this;
    }

    /**
     * @param string $URL
     * @return SaveResource
     */
    public function setURL($URL)
    {
        $this->URL = $URL;
        return $this;
    }

    /**
     * @param string $Byline
     * @return SaveResource
     */
    public function setByline($Byline)
    {
        $this->Byline = $Byline;
        return $this;
    }

    /**
     * @param string $Description
     * @return SaveResource
     */
    public function setDescription($Description)
    {
        $this->Description = $Description;
        return $this;
    }

    /**
     * @param \DateTime $PublicationDate
     * @return SaveResource
     */
    public function setPublicationDate(\DateTime $PublicationDate)
    {
        $this->PublicationDate = $PublicationDate->format('c');
        return $this;
    }

    /**
     * @param string $PricingModel
     * @return SaveResource
     */
    public function setPricingModel($PricingModel)
    {
        $this->PricingModel = $PricingModel;
        return $this;
    }

    /**
     * @param float $Price
     * @return SaveResource
     */
    public function setPrice($Price)
    {
        $this->Price = $Price;
        return $this;
    }

    /**
     * @param string $ExpirationPeriodUnit
     * @return SaveResource
     */
    public function setExpirationPeriodUnit($ExpirationPeriodUnit)
    {
        $this->ExpirationPeriodUnit = $ExpirationPeriodUnit;
        return $this;
    }

    /**
     * @param int $ExpirationPeriodValue
     * @return SaveResource
     */
    public function setExpirationPeriodValue($ExpirationPeriodValue)
    {
        $this->ExpirationPeriodValue = $ExpirationPeriodValue;
        return $this;
    }

    /**
     * @param float $TargetConversionRate
     * @return SaveResource
     */
    public function setTargetConversionRate($TargetConversionRate)
    {
        $this->TargetConversionRate = $TargetConversionRate;
        return $this;
    }

    /**
     * @param float $TargetConversionPriceFloor
     * @return SaveResource
     */
    public function setTargetConversionPriceFloor($TargetConversionPriceFloor)
    {
        $this->TargetConversionPriceFloor = $TargetConversionPriceFloor;
        return $this;
    }

    /**
     * @param int $TargetConversionHitsPerRecalculationPeriod
     * @return SaveResource
     */
    public function setTargetConversionHitsPerRecalculationPeriod($TargetConversionHitsPerRecalculationPeriod)
    {
        $this->TargetConversionHitsPerRecalculationPeriod = $TargetConversionHitsPerRecalculationPeriod;
        return $this;
    }
}