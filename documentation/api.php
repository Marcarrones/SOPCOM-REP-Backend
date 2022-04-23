<?php
require("../vendor/autoload.php");

$openapi = \OpenApi\Generator::scan(['../Controllers']);

header('Content-Type: application/json');
echo $openapi->toJSON();