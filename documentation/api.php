<?php
require("../vendor/autoload.php");
include "../Controllers/MethodElementController.php";

$openapi = \OpenApi\Generator::scan(['../Controllers']);

header('Content-Type: application/json');
echo $openapi->toJSON();