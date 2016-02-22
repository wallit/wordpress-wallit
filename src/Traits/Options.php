<?php
/**
 * The options trait - shared among all kinds of things, yo.
 *
 * @author Aaron Saray
 */

namespace iMonezaPRO\Traits;

/**
 * Class Options
 * @package iMonezaPRO\Traits
 */
trait Options
{
    /**
     * @var string the key for options
     */
    protected static $optionsKey = 'imoneza-options';

    /**
     * @return mixed|void
     */
    protected function getOptions()
    {
        return get_option(self::$optionsKey);
    }
}