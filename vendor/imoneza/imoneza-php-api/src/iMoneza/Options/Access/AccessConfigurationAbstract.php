<?php
/**
 * Abstract class used by access options classes
 *
 * @author Aaron Saray
 */

namespace iMoneza\Options\Access;

use iMoneza\Data\ResourceAccess;
use iMoneza\Options\OptionsAbstract;

/**
 * Class AccessConfigurationAbstract
 * @package iMoneza\Options\Access
 */
abstract class AccessConfigurationAbstract extends OptionsAbstract implements AccessInterface
{
    /**
     * @var string status for ad blocker
     */
    const AD_BLOCKER_STATUS_UNKNOWN = 'Unknown';

    /**
     * @var string status for ad blocker
     */
    const AD_BLOCKER_STATUS_DETECTION_NOT_PERFORMED = 'DetectionNotPerformed';

    /**
     * @var string status for ad blocker
     */
    const AD_BLOCKER_STATUS_NOT_DETECTED = 'AdBlockerNotDetected';

    /**
     * @var string status for ad blocker
     */
    const AD_BLOCKER_STATUS_DETECTED = 'AdBlockerDetected';

    /**
     * @var string
     */
    private $apiBaseUrl = 'https://accessapi.imoneza.com';
    
    /**
     * @var string the url of this resource
     */
    protected $ResourceURL = '';

    /**
     * @var string the IP address
     */
    protected $IP = '';

    /**
     * @var string
     */
    protected $AdBlockerStatus;

    /**
     * @param mixed $ResourceURL
     * @return $this
     */
    public function setResourceURL($ResourceURL)
    {
        $this->ResourceURL = $ResourceURL;
        return $this;
    }

    /**
     * @param mixed $IP
     * @return $this
     */
    public function setIP($IP)
    {
        $this->IP = $IP;
        return $this;
    }

    /**
     * @param string $AdBlockerStatus
     * @return GetResourceFromResourceKey
     */
    public function setAdBlockerStatus($AdBlockerStatus)
    {
        $this->AdBlockerStatus = $AdBlockerStatus;
        return $this;
    }

    /**
     * @return string
     */
    public function getRequestType()
    {
        return self::REQUEST_TYPE_GET;
    }

    /**
     * @return ResourceAccess
     */
    public function getDataObject()
    {
        return new ResourceAccess();
    }
}