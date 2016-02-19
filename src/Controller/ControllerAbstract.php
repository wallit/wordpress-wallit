<?php
/**
 * Controller Abstract
 *
 * @author Aaron Saray
 */

namespace iMonezaPRO\Controller;

/**
 * Class ControllerAbstract
 * @package iMonezaPRO\Controller
 */
abstract class ControllerAbstract
{
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