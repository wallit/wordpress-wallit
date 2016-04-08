<?php
/**
 * The options trait - shared among all kinds of things, yo.
 *
 * @author Aaron Saray
 */

namespace iMoneza\WordPress\Pro\Traits;

/**
 * Class Options
 * @package iMoneza\WordPress\Pro\Traits
 */
trait Options
{
    /**
     * @var string the key for options
     */
    protected static $optionsKey = 'imoneza-options';

    /**
     * @return \iMoneza\WordPress\Pro\Model\Options
     */
    protected function getOptions()
    {
        return get_option(self::$optionsKey, new \iMoneza\WordPress\Pro\Model\Options());
    }

    /**
     * @param \iMoneza\WordPress\Pro\Model\Options $options
     * @return $this
     */
    protected function saveOptions(\iMoneza\WordPress\Pro\Model\Options $options)
    {
        $options->setLastUpdatedNow();
        update_option('imoneza-options', $options);
        return $this;
    }
}