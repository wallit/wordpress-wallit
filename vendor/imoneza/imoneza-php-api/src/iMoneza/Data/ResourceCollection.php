<?php
/**
 * Collection of resources
 *
 * @author Aaron Saray
 */

namespace iMoneza\Data;

/**
 * Class ResourceCollection
 * @package iMoneza\Data
 */
class ResourceCollection extends DataAbstract implements \Iterator
{
    /**
     * @var array the resource classes
     */
    private $resources = [];

    /**
     * @var int the position in resources
     */
    private $position = 0;

    /**
     * @param array $values
     * @return $this
     */
    public function populate(array $values = [])
    {
        foreach ($values as $value) {
            $resource = new Resource();
            $resource->populate($value);
            $this->resources[] = $resource;
        }
        return $this;
    }

    /**
     * Rewind the array
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * @return Resource
     */
    public function current() {
        return $this->resources[$this->position];
    }

    /**
     * @return int
     */
    public function key() {
        return $this->position;
    }

    /**
     * progress forward
     */
    public function next() {
        ++$this->position;
    }

    /**
     * @return bool
     */
    public function valid() {
        return isset($this->resources[$this->position]);
    }
}