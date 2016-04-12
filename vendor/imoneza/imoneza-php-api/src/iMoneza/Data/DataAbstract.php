<?php
/**
 * abstract data
 *
 * @author Aaron Saray
 */

namespace iMoneza\Data;

/**
 * Class DataAbstract
 * @package iMoneza\Data
 */
abstract class DataAbstract implements DataInterface
{
    /**
     * @var array keys to create date time objects
     */
    protected $dateTimeKeys = [];

    /**
     * @var array keys to create classes
     */
    protected $classKeys = [];

    /**
     * @var array keys to create array of classes
     */
    protected $arrayClassKeys = [];

    /**
     * Populate the class
     * @param array $values
     * @return $this
     */
    public function populate(array $values = [])
    {
        foreach ($values as $key => $value) {
            $methodName = "set{$key}";
            if (method_exists($this, $methodName)) {
                if (in_array($key, $this->dateTimeKeys)) {
                    $value = new \DateTime($value, new \DateTimeZone('UTC'));
                }
                elseif (in_array($key, $this->classKeys) && !is_null($value)) {
                    $populateValue = $value;
                    $className = sprintf('%s\%s', __NAMESPACE__, $key);
                    /** @var self $value */
                    $value = new $className();
                    $value->populate($populateValue);
                }
                elseif (array_key_exists($key, $this->arrayClassKeys)) {
                    $arrayValues = $value;
                    $value = [];
                    $className = sprintf('%s\%s', __NAMESPACE__, $this->arrayClassKeys[$key]);
                    foreach ($arrayValues as $v) {
                        /** @var self $class */
                        $class = new $className();
                        $class->populate($v);
                        $value[] = $class;
                    }
                }
                $this->$methodName($value);
            }
        }

        return $this;
    }
}