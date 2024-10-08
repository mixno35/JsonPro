# JsonPro
Managing JSON files: extracting, changing, adding, deleting data.

![](https://img.shields.io/badge/PHP-5.6-blue)
![](https://img.shields.io/github/v/release/mixno35/JsonPro)
![](https://img.shields.io/packagist/dt/mixno35/json-pro)

## Installation
```
composer require mixno35/json-pro
```

## Using
### Initialization
Create a new instance of the class and initialize it by passing the path to the JSON file, use the `init` method:
```php
use Mixno35\JsonPro\JsonPro;

$json = new JsonPro();
$json->init('path/to/filename.json');
```
### Reading data
You can get the value from the JSON structure by specifying the path to the keys, use the `get` method:
```php
$value = $json->get(['key1', 'key2']);
```
To get the full structure of a JSON file, use:
```php
$value = $json->get();
```
or:
```php
$value = $json->var;
```
### Write data
To change or add data, use the `set` method:
```php
$json->set(['key1', 'key2'], 'newValue');
```
### Deleting data
To delete a value, use the `delete` method:
```php
$json->delete(['key1', 'key2']);
```
### Has data
Check if the key is in json. The `has` method is available starting with the release ![](https://img.shields.io/badge/release-v1.1-blue). Use the `has` method:
```php
if ($json->has()) {
    // Return true
}

if ($json->has(['key1'])) {
    // Return true
}

if ($json->has(['key1', 'key2', 'key3'])) {
    // Return false
}
```
### Saving data
By default, all your created changes using the `set` and `delete` methods are not saved, to save the changes, use the `save` method:
```php
$json->save();
```
### Usage example
A complete example of using JsonPro:
```php
use Mixno35\JsonPro\JsonPro;

require_once 'vendor/autoload.php';

try {
    // Initialization
    $json = new JsonPro();
    $json->init('path/to/filename.json');

    // Reading data
    $value = $json->get(['key1', 'key2']);
    
    var_dump($value);
    echo PHP_EOL;

    // Write data
    if ($json->has(['key1']) === false) {
        $json->set(['key1'], 'newValue');
        // {"key1": "newValue"}
        $json->set(['key1', 'key2'], 'newValue');
        // {"key1": {"key2": "newValue"}}   
    }

    // Deleting data
    $json->delete(['key1', 'key2']);
    
    // Saving data
    $json->save();

} catch (Exception $e) {
    echo 'Exception: ' . $e->getMessage();
}
```
### Exceptions
JsonPro can throw the following exceptions:
- `InvalidArgumentException`: If invalid arguments are passed (for example, an empty string as a path).
- `Exception`: If the file cannot be read, does not exist, is not valid JSON, or the directory is not writable/readable.
