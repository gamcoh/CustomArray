<?php

class CustomArray implements ArrayAccess {
    private $data = array();

    public function __construct(array $arr=[]) {
        $this->data = $arr;
    }

    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    public function offsetExists($offset) {
        return isset($this->data[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->data[$offset]);
    }

    public function offsetGet($offset) {
        if (is_callable($offset)) {
            $newArray = [];
            foreach ($this->data as $key => $value) {
                if ($offset($key, $value) === true) {
                    $newArray[$key] = $value;
                }
            }
            return $newArray;
        } elseif ($offset === -1) {
            return end($this->data);
        } elseif (preg_match('/^([0-9]{1,4}):$/', $offset, $index)) {
            return array_slice($this->data, $index[1], null, true);
        } elseif (preg_match('/^([0-9]{1,4}):([0-9]{1,4})$/', $offset, $index)) {
            return array_slice($this->data, $index[1], $index[2], true);
        } elseif (preg_match('/^:([0-9]{1,4})$/', $offset, $index)) {
            return array_reverse(array_slice($this->data, 0, $index[1], true));
        } elseif ($offset === ':-1') {
            return array_reverse($this->data);
        }

        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }

    public function valuesTo(callable $func)
    {
        $newArray = [];
        foreach ($this->data as $key => $value) {
            $newArray[$key] = $func($value);
        }
        $this->data = $newArray;
        return $newArray;
    }

    public function keysTo(callable $func)
    {
        $newArray = [];
        foreach ($this->data as $key => $value) {
            $newArray[$func($key)] = $value;
        }
        $this->data = $newArray;
        return $newArray;
    }
}
