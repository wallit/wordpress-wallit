<?php
/**
 * Callback Result
 *
 * @author Aaron Saray
 */

namespace iMoneza\Data;

/**
 * Class None
 * @package iMoneza\Data
 */
class CallbackResult extends DataAbstract
{
    /**
     * @var array callback data
     */
    protected $data = [];

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     * @return CallbackResult
     */
    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @param array $values
     * @return $this
     */
    public function populate(array $values = [])
    {
        $this->setData($values);
        return $this;
    }
}
