<?php
/**
 * iMoneza Service
 *
 * This is a stateful service for interacting with the API
 *
 * @author Aaron Saray
 */

namespace iMonezaPRO\Service;
use iMoneza\Connection;
use iMoneza\Data\Resource;
use iMoneza\Exception;
use iMoneza\Helper;
use iMoneza\Options\Access\ResourceFromResourceKey;
use iMoneza\Options\Management\Property;
use iMoneza\Request\Curl;
use Monolog\Logger;

/**
 * Class iMoneza
 * @package iMonezaPRO\Service
 */
class iMoneza
{
    /**
     * @var string
     */
    protected $managementApiKey;

    /**
     * @var string
     */
    protected $managementApiSecret;

    /**
     * @var string
     */
    protected $accessApiKey;

    /**
     * @var string
     */
    protected $accessApiSecret;

    /**
     * @var string the last error
     */
    protected $lastError = '';

    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @param string $managementApiKey
     * @return iMoneza
     */
    public function setManagementApiKey($managementApiKey)
    {
        $this->managementApiKey = $managementApiKey;
        return $this;
    }

    /**
     * @param string $managementApiSecret
     * @return iMoneza
     */
    public function setManagementApiSecret($managementApiSecret)
    {
        $this->managementApiSecret = $managementApiSecret;
        return $this;
    }

    /**
     * @param string $accessApiKey
     * @return iMoneza
     */
    public function setAccessApiKey($accessApiKey)
    {
        $this->accessApiKey = $accessApiKey;
        return $this;
    }

    /**
     * @param string $accessApiSecret
     * @return iMoneza
     */
    public function setAccessApiSecret($accessApiSecret)
    {
        $this->accessApiSecret = $accessApiSecret;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastError()
    {
        return $this->lastError;
    }

    /**
     * @return \iMoneza\Data\Property|false
     */
    public function getProperty()
    {
        $this->lastError = '';
        $api = $this->getConnectionInstance();

        $options = new Property();
        if ($baseUrl = getenv('MANAGEMENT_API_URL')) {
            $options->setApiBaseURL($baseUrl);
        }

        $result = false;
        try {
            /** @var \iMoneza\Data\Property $result */
            $result = $api->request($options, new \iMoneza\Data\Property());
        }
        catch (Exception\NotFound $e) {
            $this->lastError = "Oh no!  Looks like your Management API Key isn't working. You might want to check that out again.";
        }
        catch (Exception\AuthenticationFailure $e) {
            $this->lastError = "Well, we have good news and bad news.  Good news is - got an idea of who you are.  Bad news?  Looks like your Management API secret might be wrong.  Why don't you delete it and try again?  That would be swell!";
        }
        catch (Exception\iMoneza $e) {
            $this->lastError = sprintf('Something went wrong with the system: %s', $e->getMessage());
        }

        return $result;
    }

    /**
     * Stub currently
     * @return bool
     */
    public function validateResourceAccessApiCredentials()
    {
        $this->lastError = '';

        $api = $this->getConnectionInstance();

        $options = new ResourceFromResourceKey();
        $options->setResourceURL('api-validation')->setResourceKey('api-validation')->setIP(Helper::getCurrentIP());

        if ($baseUrl = getenv('ACCESS_API_URL')) {
            $options->setApiBaseURL($baseUrl);
        }

        $result = false;
        try {
            $api->request($options, new Resource());
            $result = true;
        }
        catch (Exception\NotFound $e) {
            $this->lastError = "It seems like your resource access API key is wrong. Check and see if there are any obvious problems - otherwise, delete it and try again please.";
        }
        catch (Exception\AuthenticationFailure $e) {
            $this->lastError = "Your resource access API secret looks wrong.  Can you give it another shot?";
        }
        catch (Exception\iMoneza $e) {
            $this->lastError = sprintf('Something went wrong with the system: %s', $e->getMessage());
        }

        return $result;
    }

    protected function getConnectionInstance()
    {
        if (is_null($this->connection)) {
            $logger = new Logger(__CLASS__);
            $this->connection = new Connection($this->managementApiKey, $this->managementApiSecret, $this->accessApiKey, $this->accessApiSecret, new Curl(), $logger);
        }

        return $this->connection;
    }
}