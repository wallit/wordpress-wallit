<?php
/**
 * Quota object
 *
 * @author Aaron Saray
 */

namespace iMoneza\Data;

/**
 * Class Quota
 * @package iMoneza\Data
 */
class Quota extends DataAbstract
{
    /**
     * @var boolean
     */
    protected $isEnabled;

    /**
     * @var int
     */
    protected $hitCount;

    /**
     * @var int
     */
    protected $allowedHits;

    /**
     * @var \DateTime
     */
    protected $periodStartDate;

    /**
     * @var string
     */
    protected $periodName;

    /**
     * @var boolean
     */
    protected $isMet;

    /**
     * @var array date time objects
     */
    protected $dateTimeKeys = ['PeriodStartDate'];

    /**
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->isEnabled;
    }

    /**
     * @param boolean $isEnabled
     * @return $this
     */
    public function setIsEnabled($isEnabled)
    {
        $this->isEnabled = $isEnabled;
        return $this;
    }

    /**
     * @return int
     */
    public function getHitCount()
    {
        return $this->hitCount;
    }

    /**
     * @param int $hitCount
     * @return $this
     */
    public function setHitCount($hitCount)
    {
        $this->hitCount = $hitCount;
        return $this;
    }

    /**
     * @return int
     */
    public function getAllowedHits()
    {
        return $this->allowedHits;
    }

    /**
     * @param int $allowedHits
     * @return $this
     */
    public function setAllowedHits($allowedHits)
    {
        $this->allowedHits = $allowedHits;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getPeriodStartDate()
    {
        return $this->periodStartDate;
    }

    /**
     * @param \DateTime $periodStartDate
     * @return $this
     */
    public function setPeriodStartDate(\DateTime $periodStartDate)
    {
        $this->periodStartDate = $periodStartDate;
        return $this;
    }

    /**
     * @return string
     */
    public function getPeriodName()
    {
        return $this->periodName;
    }

    /**
     * @param string $periodName
     * @return $this
     */
    public function setPeriodName($periodName)
    {
        $this->periodName = $periodName;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isMet()
    {
        return $this->isMet;
    }

    /**
     * @param boolean $isMet
     * @return $this
     */
    public function setIsMet($isMet)
    {
        $this->isMet = $isMet;
        return $this;
    }
}
