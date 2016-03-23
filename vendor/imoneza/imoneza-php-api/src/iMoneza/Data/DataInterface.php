<?php
/**
 * Data object interface
 *
 * @author Aaron Saray
 */

namespace iMoneza\Data;

/**
 * Interface DataInterface
 * @package iMoneza\Data
 */
interface DataInterface
{
    /**
     * Populate the data object
     *
     * @param array $values
     * @return mixed
     */
    public function populate(array $values = []);
}