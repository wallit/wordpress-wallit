<?php
/**
 * Abstract class for options
 *
 * @author Aaron Saray
 */

namespace iMoneza\Options;
use iMoneza\Exception;

/**
 * Class OptionsAbstract
 * @package iMoneza\Options
 */
abstract class OptionsAbstract implements OptionsInterface
{
    /**
     * @var string this is a get method
     */
    const REQUEST_TYPE_GET = 'get';

    /**
     * @var string the put method
     */
    const REQUEST_TYPE_PUT = 'put';

    /**
     * @var string the post method
     */
    const REQUEST_TYPE_POST = 'post';

    /**
     * @return array the populated array of elements from this set of options (note, private vars are not included - yay!)
     */
    public function getPopulated()
    {
        $properties = array_reduce(array_keys(get_class_vars(get_class($this))), function($holder, $currentKey) {
            if (!is_null($this->$currentKey)) $holder[$currentKey] = $this->$currentKey;
            return $holder;
        }, []);
        return $properties;
    }

    /**
     * Get the data object this should return
     *
     * @return \iMoneza\Data\DataAbstract
     */
    abstract public function getDataObject();

    /**
     * Gets the URL of the base request
     *
     * @return string get the base URL
     */
    abstract public function getApiBaseURL();

    /**
     * Sets the base URL of the request
     * @param $baseUrl
     * @return mixed
     */
    abstract public function setApiBaseURL($baseUrl);

    /**
     * @param $accessKey
     * @return mixed
     */
    abstract public function setAccessKey($accessKey);

    /**
     * @return boolean
     */
    abstract public function hasAccessKey();

    /**
     * @return mixed the custom URL for this request
     */
    abstract public function getEndPoint();

    /**
     * @return string
     */
    abstract public function getRequestType();
}