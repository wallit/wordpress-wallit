<?php
/**
 * The options trait - shared among all kinds of things, yo.
 *
 * @author Aaron Saray
 */

namespace iMoneza\WordPress\Traits;

/**
 * Class Options
 * @package iMoneza\WordPress\Traits
 */
trait Options
{
    /**
     * @var string the key for options
     */
    protected static $optionsKey = 'imoneza-options';

    /**
     * @return \iMoneza\WordPress\Model\Options
     */
    protected function getOptions()
    {
        return get_option(self::$optionsKey, new \iMoneza\WordPress\Model\Options());
    }

    /**
     * @param \iMoneza\WordPress\Model\Options $options
     * @return $this
     */
    protected function saveOptions(\iMoneza\WordPress\Model\Options $options)
    {
        $options->setLastUpdatedNow();
        update_option('imoneza-options', $options);
        return $this;
    }
}