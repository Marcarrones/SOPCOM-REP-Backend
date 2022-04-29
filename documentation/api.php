<?php
require("../vendor/autoload.php");
require "../models/Model.php";
include "../controllers/MethodElementController.php";
include "../controllers/CriterionController.php";

$openapi = \OpenApi\Generator::scan(['../Controllers']);

header('Content-Type: application/json');
echo $openapi->toJSON();