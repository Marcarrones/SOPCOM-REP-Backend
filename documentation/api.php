<?php
require("../vendor/autoload.php");
include "../Controllers/MethodElementController.php";
include "../Controllers/CriterionController.php";

$openapi = \OpenApi\Generator::scan(['../Controllers']);

header('Content-Type: application/json');
echo $openapi->toJSON();