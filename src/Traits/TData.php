<?php

namespace uSIreF\Networking\Traits;

/**
 * This file defines trait for data entity.
 *
 * @author Martin Kovar <mkovar86@gmail.com>
 */
trait TData {

    /**
     * Stored data.
     *
     * @var array
     */
    private $_data = [];

    /**
     * Gets or sets data value under name.
     *
     * @param string $name  Name identificator
     * @param mixed  $value Value
     *
     * @return mixed
     */
    protected function handleData(string $name, $value = null) {
        $result = null;
        if ($value !== null) {
            $this->_data[$name] = $value;
        }

        if (array_key_exists($name, $this->_data)) {
            $result = $this->_data[$name];
        }

        return $result;
    }

    /**
     * Returns all stored data.
     *
     * @return array
     */
    public function to(): array {
        return $this->_data;
    }

    /**
     * Rewrites all stored data by given array.
     *
     * @param array $data Data
     *
     * @return array
     */
    public function from(array $data = []): array {
        $this->_data = $data;

        return $this->_data;
    }

}