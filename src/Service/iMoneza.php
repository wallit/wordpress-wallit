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
use iMoneza\Data\None;
use iMoneza\Data\Resource;
use iMoneza\Exception;
use iMoneza\Helper;
use iMoneza\Options\Access\ResourceFromResourceKey;
use iMoneza\Options\Management\Property;
use iMoneza\Options\Management\SaveResource;
use iMoneza\Options\OptionsAbstract;
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
     * @var int used to indicate the manage API
     */
    const API_TYPE_MANAGE = 1;

    /**
     * @var int used to indicate the access API
     */
    const API_TYPE_ACCESS = 2;

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
     * @param \WP_Post $post
     * @param $pricingGroupId
     * @return bool
     */
    public function createOrUpdateResource(\WP_Post $post, $pricingGroupId)
    {
        $options = new SaveResource();
        $options->setPricingGroupId($pricingGroupId)
            ->setExternalKey('wp-' . $post->ID)
            ->setName($post->post_title)
            ->setTitle($post->post_title);
        $this->prepareForPost($options);

        $result = false;
        try {
            $this->getConnectionInstance()->request($options, new None());
            $result = true;
        }
        catch (Exception\iMoneza $e) {
            $this->lastError = sprintf('Something went wrong with the system: %s', $e->getMessage());
        }

        return $result;
    }

    /**
     * @return \iMoneza\Data\Property|false
     */
    public function getProperty()
    {
        $options = new Property();
        $this->prepareForPost($options);

        $result = false;
        try {
            /** @var \iMoneza\Data\Property $result */
            $result = $this->getConnectionInstance()->request($options, new \iMoneza\Data\Property());
        }
        catch (Exception\NotFound $e) {
            $this->lastError = "Oh no!  Looks like your Management API Key isn't working. Look closely - does it look right?";
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
        $options = new ResourceFromResourceKey();
        $options->setResourceURL('api-validation')->setResourceKey('api-validation')->setIP(Helper::getCurrentIP());
        $this->prepareForPost($options, self::API_TYPE_ACCESS);

        $result = false;
        try {
            $this->getConnectionInstance()->request($options, new Resource());
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

    /**
     * @return Connection
     */
    protected function getConnectionInstance()
    {
        if (is_null($this->connection)) {
            $logger = new Logger(__CLASS__);
            if (WP_DEBUG_LOG) {
                $logger->pushHandler(new StreamHandler('php://stderr'));
            }
            $this->connection = new Connection($this->managementApiKey, $this->managementApiSecret, $this->accessApiKey, $this->accessApiSecret, new Curl(), $logger);
        }

        return $this->connection;
    }

    /**
     * @param OptionsAbstract $options
     * @param int $type
     */
    protected function prepareForPost(OptionsAbstract $options, $type = self::API_TYPE_MANAGE)
    {
        $this->lastError = '';
        if ($baseUrl = getenv($type == self::API_TYPE_MANAGE ? 'MANAGEMENT_API_URL' : 'ACCESS_API_URL')) {
            $options->setApiBaseURL($baseUrl);
        }
    }
}