<?php
/**
 * Property
 *
 * @author Aaron Saray
 */

namespace iMoneza\Data;

/**
 * Class Property
 * @package iMoneza\Data
 */
class Property extends DataAbstract
{
    /**
     * @var string
     */
    const QUOTA_PERIOD_MONTHLY = 'Monthly';

    /**
     * @var array the keys that are converted to models
     */
    protected $arrayClassKeys = ['PricingGroups'=>'PricingGroup', 'SubscriptionGroups'=>'SubscriptionGroup'];

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var boolean
     */
    protected $dynamicallyCreateResources;

    /**
     * @var boolean
     */
    protected $enableQuota;

    /**
     * @var boolean
     */
    protected $enableSubscriptions;

    /**
     * @var boolean
     */
    protected $enableSinglePurchases;

    /**
     * @var boolean
     */
    protected $alwaysRequireAuthentication;

    /**
     * @var boolean
     */
    protected $freeResourcesRequireAuthentication;

    /**
     * @var int
     */
    protected $quota;

    /**
     * @var string
     */
    protected $quotaPeriod;

    /**
     * @var array
     */
    protected $SubscriptionGroups;

    /**
     * @var array
     */
    protected $PricingGroups;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Property
     */
    public function setName($name)
    {
        $this->name = $name;
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
     * @return Property
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isDynamicallyCreateResources()
    {
        return $this->dynamicallyCreateResources;
    }

    /**
     * @param boolean $dynamicallyCreateResources
     * @return Property
     */
    public function setDynamicallyCreateResources($dynamicallyCreateResources)
    {
        $this->dynamicallyCreateResources = $dynamicallyCreateResources;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isEnableQuota()
    {
        return $this->enableQuota;
    }

    /**
     * @param boolean $enableQuota
     * @return Property
     */
    public function setEnableQuota($enableQuota)
    {
        $this->enableQuota = $enableQuota;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isEnableSubscriptions()
    {
        return $this->enableSubscriptions;
    }

    /**
     * @param boolean $enableSubscriptions
     * @return Property
     */
    public function setEnableSubscriptions($enableSubscriptions)
    {
        $this->enableSubscriptions = $enableSubscriptions;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isEnableSinglePurchases()
    {
        return $this->enableSinglePurchases;
    }

    /**
     * @param boolean $enableSinglePurchases
     * @return Property
     */
    public function setEnableSinglePurchases($enableSinglePurchases)
    {
        $this->enableSinglePurchases = $enableSinglePurchases;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isFreeResourcesRequireAuthentication()
    {
        return $this->freeResourcesRequireAuthentication;
    }

    /**
     * @param boolean $freeResourcesRequireAuthentication
     * @return Property
     */
    public function setFreeResourcesRequireAuthentication($freeResourcesRequireAuthentication)
    {
        $this->freeResourcesRequireAuthentication = $freeResourcesRequireAuthentication;
        return $this;
    }

    /**
     * @return int
     */
    public function getQuota()
    {
        return $this->quota;
    }

    /**
     * @param int $quota
     * @return Property
     */
    public function setQuota($quota)
    {
        $this->quota = $quota;
        return $this;
    }

    /**
     * @return string
     */
    public function getQuotaPeriod()
    {
        return $this->quotaPeriod;
    }

    /**
     * @param string $quotaPeriod
     * @return Property
     */
    public function setQuotaPeriod($quotaPeriod)
    {
        $this->quotaPeriod = $quotaPeriod;
        return $this;
    }

    /**
     * @return array
     */
    public function getSubscriptionGroups()
    {
        return $this->SubscriptionGroups;
    }

    /**
     * @param array $SubscriptionGroups
     * @return Property
     */
    public function setSubscriptionGroups(array $SubscriptionGroups)
    {
        $this->SubscriptionGroups = $SubscriptionGroups;
        return $this;
    }

    /**
     * @return array
     */
    public function getPricingGroups()
    {
        return $this->PricingGroups;
    }

    /**
     * @param array $PricingGroups
     * @return Property
     */
    public function setPricingGroups(array $PricingGroups)
    {
        $this->PricingGroups = $PricingGroups;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isAlwaysRequireAuthentication()
    {
        return $this->alwaysRequireAuthentication;
    }

    /**
     * @param boolean $alwaysRequireAuthentication
     * @return Property
     */
    public function setAlwaysRequireAuthentication($alwaysRequireAuthentication)
    {
        $this->alwaysRequireAuthentication = $alwaysRequireAuthentication;
        return $this;
    }
}
