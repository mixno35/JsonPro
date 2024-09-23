<?php
require_once "vendor/autoload.php";

use Mixno35\JsonPro\JsonPro;

echo PHP_EOL;

$jsonPro = new JsonPro();

$array = array(
    "key1" => "value1",
    "key2" => "value2"
);

try {
    $jsonPro->init("test.json", true);

    var_dump($jsonPro->delete(["key3"]));
//    $jsonPro->save();

    var_dump($jsonPro->get());
} catch (Exception $e) {
    var_dump($e);
}