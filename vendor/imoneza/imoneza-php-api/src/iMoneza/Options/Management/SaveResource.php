<?php
/**
 * Save (PUT) a resource
 *
 * @author Aaron Saray
 */

namespace iMoneza\Options\Management;

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
     * @param $pricingGroupId
     * @return $this
     */
    public function setPricingGroupId($pricingGroupId)
    {
        $this->PricingGroup = ['PricingGroupID'=>$pricingGroupId];
        return $this;
    }

    /**
     * @return string
     */
    public function getExternalKey()
    {
        return $this->ExternalKey;
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
     * @return string
     */
    public function getName()
    {
        return $this->Name;
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
     * @return string
     */
    public function getTitle()
    {
        return $this->Title;
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
}