<?php
/**
 * Purchase
 *
 * @author Aaron Saray
 */

namespace iMoneza\Data;

/**
 * Class Purchase
 * @package iMoneza\Data
 */
class Purchase extends DataAbstract
{
    /**
     * @var boolean
     */
    protected $isPurchased;

    /**
     * @return boolean
     */
    public function isPurchased()
    {
        return $this->isPurchased;
    }

    /**
     * @param boolean $isPurchased
     * @return Purchase
     */
    public function setIsPurchased($isPurchased)
    {
        $this->isPurchased = $isPurchased;
        return $this;
    }
}