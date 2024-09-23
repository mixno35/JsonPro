<?php

namespace Mixno35\JsonPro;

use Exception;
use http\Exception\InvalidArgumentException;

class JsonPro {

    /**
     * @var string
     */
    public $path;
    /**
     * @var array
     */
    public $var;


    /**
     * Initializes the object by reading JSON data from the specified file path.
     *
     * @param string $path Path to the JSON file.
     * @param bool $use_include_path Whether to search in the include path (default is false).
     *
     * @throws Exception If the file cannot be read, does not exist, or is not a valid JSON file.
     */
    public function init($path, $use_include_path = false) {
        if (!is_string($path) || empty($path)) {
            throw new InvalidArgumentException("The path must be a non-empty string.");
        }

        $this->path = $path;
        $this->var = $this->read($use_include_path);
    }


    /**
     * Reads and decodes a JSON file into an array.
     *
     * @param bool $use_include_path Whether to search in the include path (default is false).
     * @return array Returns the decoded JSON as an array.
     *
     * @throws Exception If the file does not exist, is not a file, or is not readable.
     */
    private function read($use_include_path = false) {
        $path = $this->path;

        if (!file_exists($path)) {
            throw new Exception("The file does not exist.");
        }

        if (!is_file($path)) {
            throw new Exception("The path is not a valid file.");
        }

        if (!is_readable($path)) {
            throw new Exception("The file is not readable.");
        }

        $var = file_get_contents($path, $use_include_path);

        if ($var === false) {
            throw new Exception("Failed to read the file.");
        }

        $var = json_decode($var, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("JSON decoding error: " . json_last_error_msg());
        }

        return (array) $var;
    }

    /**
     * Saves JSON data to a specified file.
     *
     * @return false|int Returns the number of bytes written to the file, or false on failure.
     *
     * @throws InvalidArgumentException If the provided JSON data is neither an array nor a string.
     * @throws Exception If the directory is not writable.
     */
    public function save() {
        $path = $this->path;
        $var = $this->var;

        $dir = dirname($path);

        if (!is_writable($dir)) {
            throw new Exception("The directory is not writable.");
        }

        if (!is_array($var)) {
            throw new Exception("JSON is not valid array.");
        }

        $var = json_encode($var, JSON_PRETTY_PRINT);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Failed to encode JSON: " . json_last_error_msg());
        }

        return file_put_contents($path, $var);
    }

    /**
     * Retrieves a value from the JSON structure using a specified key path.
     *
     * @param array $key_path An array of keys representing the path to the desired value.
     * @return mixed|null The value at the specified key path, or null if not found.
     */
    public function get($key_path = []) {
        $result = $this->var;
        foreach ($key_path as $key) {
            if (isset($result[$key])) {
                $result = $result[$key];
            } else {
                return null;
            }
        }
        return $result;
    }

    /**
     * Sets a value in the JSON structure at the specified key path.
     *
     * @param array $key_path An array of keys representing the path where the value should be set.
     * @param mixed $value The value to set at the specified key path.
     * @return void
     * @throws Exception If the key path is invalid or cannot be set.
     */
    public function set($key_path = [], $value = null) {
        if (!is_array($key_path)) {
            throw new InvalidArgumentException("The key path must be an array.");
        }

        $temp = &$this->var;
        foreach ($key_path as $key) {
            if (!isset($temp[$key])) {
                $temp[$key] = [];
            }
            $temp = &$temp[$key];
        }
        $temp = $value;
    }

    /**
     * Deletes a value from the JSON structure at the specified key path.
     *
     * @param array $key_path An array of keys representing the path to the value to delete.
     * @return bool Returns true if the value was deleted, false if it was not found.
     */
    public function delete($key_path = []) {
        if (!is_array($key_path)) {
            throw new InvalidArgumentException("The key path must be an array.");
        }

        $result = false;
        $temp = &$this->var;
        $lastKey = array_pop($key_path);

        foreach ($key_path as $key) {
            if (isset($temp[$key])) {
                $temp = &$temp[$key];
                $result = true;
            } else {
                return false; // Early return if key path is invalid
            }
        }

        if (isset($temp[$lastKey])) {
            unset($temp[$lastKey]);
            $result = true;
        }

        return $result;
    }
}