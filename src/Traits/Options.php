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
     * @return \iMonezaPRO\Model\Options
     */
    protected function getOptions()
    {
        return get_option(self::$optionsKey, new \iMonezaPRO\Model\Options());
    }

    /**
     * @param \iMonezaPRO\Model\Options $options
     * @return $this
     */
    protected function saveOptions(\iMonezaPRO\Model\Options $options)
    {
        $options->setLastUpdatedNow();
        update_option('imoneza-options', $options);
        return $this;
    }
}