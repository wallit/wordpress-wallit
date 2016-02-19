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
use iMoneza\Exception;
use iMoneza\Options\Management\Property;
use iMoneza\Request\Curl;
use Monolog\Handler\StreamHandler;
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
     * @var string the last error
     */
    protected $lastError = '';

    /**
     * @return string
     */
    public function getManagementApiKey()
    {
        return $this->managementApiKey;
    }

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
     * @return string
     */
    public function getManagementApiSecret()
    {
        return $this->managementApiSecret;
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
     * @return string
     */
    public function getLastError()
    {
        return $this->lastError;
    }

    /**
     * @return bool|string
     */
    public function getPropertyTitle()
    {
        // @todo abstract this out
        $logger = new Logger(__CLASS__); // @todo figure out a better logging approach

        $api = new Connection($this->managementApiKey, $this->managementApiSecret, new Curl(), $logger);
        $options = new Property();
        if ($baseUrl = getenv('MANAGEMENT_API_URL')) {
            $options->setApiBaseURL($baseUrl);
        }

        $result = false;
        try {
            /** @var \iMoneza\Data\Property $data */
            $data = $api->request($options, new \iMoneza\Data\Property());
            $result = $data->getTitle();
        }
        catch (Exception\NotFound $e) {
            $this->lastError = "Oh no!  Looks like your API Key isn't working. You might want to check that out again.";
        }
        catch (Exception\AuthenticationFailure $e) {
            $this->lastError = "Well, we have good news and bad news.  Good news is - got an idea of who you are.  Bad news?  Looks like your API secret might be wrong.  Why don't you delete it and try again?  That would be swell!";
        }
        catch (Exception\iMoneza $e) {
            $this->lastError = sprintf('Something went wrong with the system: %s', $e->getMessage());
        }

        return $result;
    }
}