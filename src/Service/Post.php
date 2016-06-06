<?php
/**
 * WordPress Post Service
 *
 * @author Aaron Saray
 */

namespace iMoneza\WordPress\Service;

/**
 * Class Post
 * @package iMoneza\WordPress\Service
 */
class Post
{
    /**
     * @var int the number of posts to push out to imoneza each time a process runs to update dynamically create
     */
    const BATCH_SIZE_PROCESS_TO_IMONEZA = 20;

    /**
     * This query returns the maximum limit of items that need to be processed yet.
     * 
     * @return \WP_Query
     */
    public function getWPQueryPostsNotPriced()
    {
        return new \WP_Query([
            'post_type' =>  'post',
            'posts_per_page'    =>  self::BATCH_SIZE_PROCESS_TO_IMONEZA,
            'meta_query'    =>  [
                ['key'=>'_pricing-group-id', 'value'=>'', 'compare'=>'NOT EXISTS']
            ]
        ]);
    }
}