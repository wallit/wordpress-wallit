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
use iMoneza\Data\ResourceAccess;
use iMoneza\Exception;
use iMoneza\Helper;
use iMoneza\Options\Access\GetResourceFromResourceKey;
use iMoneza\Options\Access\GetResourceFromTemporaryUserToken;
use iMoneza\Options\Management\GetProperty;
use iMoneza\Options\Management\SaveResource;
use iMoneza\Options\OptionsAbstract;
use iMoneza\Request\Curl;
use iMonezaPRO\Filter\ExternalResourceKey;
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
     * Gets a URL to redirect for access OR false if its granted
     *
     * @param \WP_Post $post
     * @param null $iMonezaTUT
     * @return bool|string
     * @throws Exception\DecodingError
     * @throws Exception\TransferError
     */
    public function getResourceAccessRedirectURL(\WP_Post $post, $iMonezaTUT = null)
    {
        $result = false;
        $keyFilter = new ExternalResourceKey();
        
        if ($iMonezaTUT) {
            $options = new GetResourceFromTemporaryUserToken();
            $options->setTemporaryUserToken($iMonezaTUT);
        }
        else {
            $options = new GetResourceFromResourceKey();
            $options->setUserToken($this->getUserTokenFromCookie());
        }
        
        $options->setResourceKey($keyFilter->filter($post))
            ->setIP(Helper::getCurrentIP());
        $this->prepareForRequest($options, self::API_TYPE_ACCESS);

        /** @var \iMoneza\Data\ResourceAccess $data */
        $data = $this->getConnectionInstance()->request($options, $options->getDataObject());

        $this->setUserTokenCookie($data->getUserToken());

        if ($data->getAccessAction() != ResourceAccess::ACCESS_ACTION_GRANT) {
            $result = $data->getAccessActionUrl();
        }

        return $result;
    }

    /**
     * @param \WP_Post $post
     * @param $pricingGroupId
     * @return bool
     */
    public function createOrUpdateResource(\WP_Post $post, $pricingGroupId)
    {
        $keyFilter = new ExternalResourceKey();
        $options = new SaveResource();
        $options->setPricingGroupId($pricingGroupId)
            ->setExternalKey($keyFilter->filter($post))
            ->setName($post->post_title)
            ->setTitle($post->post_title);
        $this->prepareForRequest($options);

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
        $options = new GetProperty();
        $this->prepareForRequest($options);

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
        $options = new GetResourceFromResourceKey();
        $options->setResourceURL('api-validation')->setResourceKey('api-validation')->setIP(Helper::getCurrentIP());
        $this->prepareForRequest($options, self::API_TYPE_ACCESS);

        $result = false;
        try {
            $this->getConnectionInstance()->request($options, $options->getDataObject());
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
    protected function prepareForRequest(OptionsAbstract $options, $type = self::API_TYPE_MANAGE)
    {
        $this->lastError = '';
        if ($baseUrl = getenv($type == self::API_TYPE_MANAGE ? 'MANAGEMENT_API_URL' : 'ACCESS_API_URL')) {
            $options->setApiBaseURL($baseUrl);
        }
    }

    /**
     * Sets the user token cookie way in the future and HTTP only
     * @param $userToken
     */
    protected function setUserTokenCookie($userToken)
    {
        setcookie('imoneza-user-token', $userToken, 1893456000, '/', null, null, true); // 01/01/20130 0:0:0
    }

    /**
     * Get the current user token
     *
     * @return string|null
     */
    protected function getUserTokenFromCookie()
    {
        return isset($_COOKIE['imoneza-user-token']) ? $_COOKIE['imoneza-user-token'] : null;
    }
}