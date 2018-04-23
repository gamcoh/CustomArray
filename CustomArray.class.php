<?php

class CustomArray implements ArrayAccess {
    private $data = array();
    private $aFuncs = [];

    public function createFunc(string $name, callable $func)
    {
        if (method_exists($this, $name)) {
            return "Can't redefined method. Find another name.";
        }

        $this->aFuncs[$name] = $func;
    }

    public function __call(string $name, array $args)
    {
        if (isset($this->aFuncs[$name])) {
            return $this->aFuncs[$name]($this->data, $args);
        }
    }

    public function __construct(array $arr=[])
    {
        $this->data = $arr;
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    public function offsetGet($offset)
    {
        if (is_callable($offset)) {
            $newArray = [];
            foreach ($this->data as $key => $value) {
                if ($offset($key, $value) === true) {
                    $newArray[$key] = $value;
                }
            }
            $res = $newArray;
        } elseif ($offset === -1) {
            $res = end($this->data);
        } elseif (preg_match('/^([0-9]{1,4}):$/', $offset, $index)) {
            $res = array_slice($this->data, $index[1], null, true);
        } elseif (preg_match('/^([0-9]{1,4}):([0-9]{1,4})$/', $offset, $index)) {
            $res = array_slice($this->data, $index[1], $index[2], true);
        } elseif (preg_match('/^:([0-9]{1,4})$/', $offset, $index)) {
            $res = array_slice($this->data, 0, $index[1], true);
        } elseif ($offset === ':-1') {
            $res = array_reverse($this->data);
        } else {
            return $this->data[$offset];
        }

        return $res;
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

    public function arsort(): array
    {
        arsort($this->data);
        return $this->data;
    }

    public function retrieve(): array
    {
        return $this->data;
    }

    public function add($el): array
    {
        $this->data[] = $el;
        return $this->data;
    }
}

