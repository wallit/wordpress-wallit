<?php
/**
 * iMoneza Service
 *
 * @author Aaron Saray
 */

namespace iMonezaPRO\Service;

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
     * iMoneza constructor.
     * @param $managementApiKey string
     * @param $managementApiSecret string
     */
    public function __construct($managementApiKey, $managementApiSecret)
    {
        $this->managementApiKey = $managementApiKey;
        $this->managementApiSecret = $managementApiSecret;
    }

    public function getPropertyTitle()
    {
        sleep(2);
        if ($this->managementApiSecret == 'asdf') {
            return false;

        }
        return 'iMoneza Journal';
    }
}