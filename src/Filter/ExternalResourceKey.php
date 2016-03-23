<?php
/**
 * Generate an external resource key
 *
 * @author Aaron Saray
 */

namespace iMonezaPRO\Filter;

/**
 * Class ExternalResourceKey
 * @package iMonezaPRO\Filter
 */
class ExternalResourceKey
{
    /**
     * Get the external resource key
     * @param \WP_Post $post
     * @return string
     */
    public function filter(\WP_Post $post)
    {
        return sprintf('wp-%', $post->ID);
    }
}