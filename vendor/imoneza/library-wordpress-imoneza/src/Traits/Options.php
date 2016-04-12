<?php
/**
 * The options trait - shared among all kinds of things, yo.
 *
 * @author Aaron Saray
 */

namespace iMoneza\Library\WordPress\Traits;

/**
 * Class Options
 * @package iMoneza\Library\WordPress\Traits
 */
trait Options
{
    /**
     * @var string the key for options
     */
    protected static $optionsKey = 'imoneza-options';

    /**
     * @return \iMoneza\Library\WordPress\Model\Options
     */
    protected function getOptions()
    {
        return get_option(self::$optionsKey, new \iMoneza\Library\WordPress\Model\Options());
    }

    /**
     * @param \iMoneza\Library\WordPress\Model\Options $options
     * @return $this
     */
    protected function saveOptions(\iMoneza\Library\WordPress\Model\Options $options)
    {
        $options->setLastUpdatedNow();
        update_option('imoneza-options', $options);
        return $this;
    }
}