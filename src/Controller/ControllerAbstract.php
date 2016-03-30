<?php
/**
 * Controller Abstract
 *
 * @author Aaron Saray
 */

namespace iMoneza\WordPress\Controller;
use iMoneza\WordPress\Traits;

/**
 * Class ControllerAbstract
 * @package iMoneza\WordPress\Controller
 */
abstract class ControllerAbstract
{
    use Traits\Options;

    /**
     * ControllerAbstract constructor.
     */
    public function __construct()
    {
        if (!current_user_can('manage_options')) {
            wp_die(__( 'You do not have sufficient permissions to access this page.', 'iMoneza'), 403);
        }
    }

    /**
     * @return bool
     */
    protected function isPro()
    {
        return substr(plugin_basename(__FILE__), 0, 11) == 'imoneza-pro';
    }

    /**
     * @return array the most generic of responses
     */
    protected function getGenericAjaxResultsObject()
    {
        return [
            'success'   =>  false,
            'data'  =>  [
                'message'   =>  ''
            ]
        ];
    }

    /**
     * @return bool
     */
    protected function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }

    /**
     * @param $key
     * @param null $default
     * @return null|mixed
     */
    protected function getGet($key, $default = null)
    {
        return $this->getRequest($_GET, $key, $default);
    }

    /**
     * @param $key
     * @param null $default
     * @return null|mixed
     */
    protected function getPost($key, $default = null)
    {
        return $this->getRequest($_POST, $key, $default);
    }

    /**
     * Gets the requested type - just a code saver
     *
     * @param $variable
     * @param $key
     * @param $default
     * @return mixed
     */
    private function getRequest(array $variable, $key, $default)
    {
        $return = $default;
        if (array_key_exists($key, $variable)) {
            $return = $variable[$key];
        }
        return $return;
    }
}