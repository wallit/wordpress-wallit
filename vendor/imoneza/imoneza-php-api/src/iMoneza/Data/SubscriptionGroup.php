<?php
/**
 * Subscription Group object
 *
 * @author Aaron Saray
 */

namespace iMoneza\Data;

/**
 * Class SubscriptionGroup
 * @package iMoneza\Data
 */
class SubscriptionGroup extends DataAbstract
{
    /**
     * @var string the monthly period
     */
    const PERIOD_MONTHLY = 'Monthly';

    /**
     * @var string
     */
    protected $SubscriptionGroupID;

    /**
     * @var string
     */
    protected $Name;

    /**
     * @var string
     */
    protected $Title;

    /**
     * @var float
     */
    protected $Price;

    /**
     * @var string
     */
    protected $Period;

    /**
     * @return string
     */
    public function getSubscriptionGroupID()
    {
        return $this->SubscriptionGroupID;
    }

    /**
     * @param string $SubscriptionGroupID
     * @return SubscriptionGroup
     */
    public function setSubscriptionGroupID($SubscriptionGroupID)
    {
        $this->SubscriptionGroupID = $SubscriptionGroupID;
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
     * @return SubscriptionGroup
     */
    public function setName($Name)
    {
        $this->Name = $Name;
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
     * @param string $Title
     * @return SubscriptionGroup
     */
    public function setTitle($Title)
    {
        $this->Title = $Title;
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
     * @return SubscriptionGroup
     */
    public function setPrice($Price)
    {
        $this->Price = $Price;
        return $this;
    }

    /**
     * @return string
     */
    public function getPeriod()
    {
        return $this->Period;
    }

    /**
     * @param string $Period
     * @return SubscriptionGroup
     */
    public function setPeriod($Period)
    {
        $this->Period = $Period;
        return $this;
    }
}
