<?php
/**
 * Options Interface
 *
 * @author Aaron Saray
 */

namespace iMoneza\Options;

/**
 * Interface OptionsInterface
 * @package iMoneza\Options
 */
interface OptionsInterface
{
    /**
     * @return array the populated array of elements from this set of options (note, private vars are not included - yay!)
     */
    public function getPopulated();

    /**
     * Get the data object this should return
     *
     * @return \iMoneza\Data\DataAbstract
     */
    public function getDataObject();

    /**
     * Gets the URL of the base request
     *
     * @return string get the base URL
     */
    public function getApiBaseURL();

    /**
     * Sets the base URL of the request
     * @param $baseUrl
     * @return mixed
     */
    public function setApiBaseURL($baseUrl);

    /**
     * @param $accessKey
     * @return mixed
     */
    public function setAccessKey($accessKey);

    /**
     * @return boolean
     */
    public function hasAccessKey();

    /**
     * @return mixed the custom URL for this request
     */
    public function getEndPoint();

    /**
     * @return string
     */
    public function getRequestType();
}