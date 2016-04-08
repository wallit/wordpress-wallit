<?php
/**
 * Generate an external resource key
 *
 * @author Aaron Saray
 */

namespace iMoneza\WordPress\Pro\Filter;

/**
 * Class ExternalResourceKey
 * @package iMoneza\Wordpress\Filter
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
        return sprintf('wp-%s', $post->ID);
    }
}