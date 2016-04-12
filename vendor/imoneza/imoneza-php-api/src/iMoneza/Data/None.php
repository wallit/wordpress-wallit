<?php
/**
 * Empty/None
 *
 * This is just for empty responses.
 *
 * @author Aaron Saray
 */

namespace iMoneza\Data;

/**
 * Class None
 * @package iMoneza\Data
 */
class None extends DataAbstract
{
    /**
     * @param $key
     * @param $value
     * @return $this
     */
    public function __call($key, $value)
    {
        return $this;
    }
}
