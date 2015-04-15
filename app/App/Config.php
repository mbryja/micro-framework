<?php
namespace your\namespace\App;

class Config implements \ArrayAccess
{
    public $properties = array();

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->properties[] = $value;
        } else {
            $this->properties[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->properties[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->properties[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->properties[$offset]) ? $this->properties[$offset] : null;
    }
}
