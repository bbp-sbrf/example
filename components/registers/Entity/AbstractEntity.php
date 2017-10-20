<?php

namespace app\components\registers\Entity;

abstract class AbstractEntity
{
    /**
     * AbstractEntity constructor.
     * @param \stdClass|array|null $parameters
     */
    public function __construct($parameters = null)
    {
        if (!$parameters) {
            return;
        }
        if ($parameters instanceof \stdClass) {
            $parameters = get_object_vars($parameters);
        }
        $this->build($parameters);
    }

    /**
     * @param array $parameters
     */
    public function build(array $parameters)
    {
        foreach ($parameters as $property => $value) {
            $property = static::convertToCamelCase($property);
            if (property_exists($this, $property)) {
                $this->$property = $value;
            }
        }
    }

    /**
     * @param string $str
     * @return string
     */
    protected static function convertToCamelCase($str)
    {
        $callback = function ($match) {
            return strtoupper($match[2]);
        };
        return lcfirst(preg_replace_callback('/(^|_)([a-z])/', $callback, $str));
    }
}
