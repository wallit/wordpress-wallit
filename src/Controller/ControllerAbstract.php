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
    protected function getPost($key, $default = null)
    {
        $return = $default;

        if (array_key_exists($key, $_POST)) {
            $return = $_POST[$key];
        }

        return $return;
    }
}