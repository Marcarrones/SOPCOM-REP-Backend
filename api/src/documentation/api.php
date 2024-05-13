<?php
require("../vendor/autoload.php");
require "../models/Model.php";
include "../controllers/MethodElementController.php";
include "../controllers/CriterionController.php";
include "../controllers/GoalController.php";
include "../controllers/MethodChunkController.php";
include "../controllers/MapController.php";
include "../controllers/StrategyController.php";
include "../controllers/RepositoryController.php";

$openapi = \OpenApi\Generator::scan(['../controllers']);

header('Content-Type: application/json');
echo $openapi->toJSON();