<?php

namespace JDodds\Storage;
use ArrayAccess;

class KeyValueFile implements ArrayAccess {
    private $store = array();
    private $storePath;

    public function __construct($storePath) {
        $this->storePath = $storePath;
        $this->load();
    }

    public function offsetExists($offset) {
        return isset($this->store[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this->store[$offset]) ? $this->store[$offset] : null;
    }

    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->store[] = $value;
        } else {
            $this->store[$offset] = $value;
        }
    }

    public function offsetUnset($offset) {
        unset($this->store[$offset]);
    }

    public function attach($key, $value) {
        $this->offsetSet($key, $value);
    }

    public function detach($key) {
        $value = null;
        if (isset($this->store[$key])) {
            $value = $this->store[$key];
            unset($this->store[$key]);
        }
        return $value;
    }

    public function contains($key) {
        return array_key_exists($key, $this->store);
    }

    public function persist() {
        $serialized = serialize($this->store);
        file_put_contents($this->storePath, $serialized);
    }

    public function load() {
        $serialized = file_get_contents($this->storePath);
        if ($serialized !== false) {
            $this->store = unserialize($serialized);
        }
    }
}
