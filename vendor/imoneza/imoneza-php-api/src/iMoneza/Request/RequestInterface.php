<?php
/**
 * The interface for request objects
 *
 * @author Aaron Saray
 */

namespace iMoneza\Request;

/**
 * Interface RequestInterface
 * @package iMoneza\Request
 */
interface RequestInterface
{
    /**
     * Sets the URL for this request
     *
     * @param $url
     * @return RequestInterface
     */
    public function setUrl($url);

    /**
     * Sets the request type
     *
     * @param $requestType
     * @param $payload string
     * @return RequestInterface
     */
    public function setRequestTypeAndPayload($requestType, $payload = '');

    /**
     * Sets the authentication token of this particular request
     *
     * @param $token
     * @return $this
     */
    public function setAuthentication($token);

    /**
     * Sets the timestamp
     *
     * @param $timestamp
     * @return $this
     */
    public function setTimestamp($timestamp);

    /**
     * Runs the request
     * @return mixed
     */
    public function execute();

    /**
     * Gets response information unique to this request
     * @return mixed
     */
    public function getResponseInfo();

    /**
     * @return integer the HTTP Code that was returned
     */
    public function getResponseHTTPCode();

    /**
     * @return string the error message
     */
    public function getErrorString();

    /**
     * @return integer the error code
     */
    public function getErrorCode();
}