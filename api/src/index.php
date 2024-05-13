<?php

    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Origin, X-API-KEY, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Access-Control-Allow-Headers, Authorization, observe, enctype, Content-Length, X-Csrf-Token");
    require "/var/www/html/models/Model.php";
    require $_SERVER['DOCUMENT_ROOT'] . '/controllers/MethodElementController.php';
    require $_SERVER['DOCUMENT_ROOT'] . '/controllers/CriterionController.php';
    require $_SERVER['DOCUMENT_ROOT'] . '/controllers/GoalController.php';
    require $_SERVER['DOCUMENT_ROOT'] . '/controllers/MethodChunkController.php';
    require $_SERVER['DOCUMENT_ROOT'] . '/controllers/MapController.php';
    require $_SERVER['DOCUMENT_ROOT'] . '/controllers/StrategyController.php';
    require $_SERVER['DOCUMENT_ROOT'] . '/controllers/RepositoryController.php';
    
    # $uri[0] index.php / $uri[1] / $uri[2] ...
    $uri = explode('/', parse_url($_SERVER['PATH_INFO'], PHP_URL_PATH));
	$method = $_SERVER['REQUEST_METHOD'];

    switch($uri[1]) {
        case 'method-element':
            $controller = new MethodElementController();
            switch($method) {
                case 'GET':
                    if(isset($uri[2])) {
                        if($uri[2] == "types") {
                            $controller->getAllMethodElementTypes(); #GET /method-element/types
                        } else if($uri[2] == "relations" && $uri[3] == "types") {
                            $controller->getAllMethodElementRelationTypes(); #GET /method-element/relations/types
                        } else {
                            $controller->getMethodElement($uri[2]); #GET /method-element/:id
                        }
                    } else {
                        $controller->getAllMethodElement(); #GET /method-element
                    }
                    break;
                case 'POST':
                    if(isset($uri[2])) {
                        if(isset($uri[3])) {
                            if($uri[3] == 'image') {
                                $controller->addMethodElementImage($uri[2]);
                            }
                        }
                    } else {
                        $controller->addNewMethodElement();
                    }
                    break;
                case 'DELETE':
                    if(isset($uri[2])) {
                        $controller->deleteMethodElement($uri[2]); #DELETE /method-element/:id
                    } else {
                        http_response_code(404);
                    }
                    break;
                case 'PUT':
                    if(isset($uri[2])) {
                        $controller->updateMethodELement($uri[2]); #PUT /method-element/:id
                    } else {
                        http_response_code(404);
                    }
                    break;
                case 'OPTIONS':
                    http_response_code(200);
                    break;
                default:
                    http_response_code(404);
                    break;
            }
            break;
        case 'method-chunk':
            $controller = new MethodChunkController();
            switch($method) {
                case 'GET':
                    if(isset($uri[2])){
                        $controller->getMethodChunk($uri[2]); #GET /method-chunk/:id
                    } else {
                        $controller->getAllMethodChunk(); #GET /method-chunk
                    }
                    break;
                case 'POST':
                    if(isset($ur[2])){

                    } else {
                        $controller->addNewMethodChunk(); #POST /method-chunk
                    }
                    break;
                case 'PUT':
                    if(isset($uri[2])) {
                        $controller->updateMethodChunk($uri[2]); #PUT /method-chunk/:id
                    } else {
                        http_response_code(404);
                    }
                    break;
                case 'DELETE':
                    if(isset($uri[2])) {
                        $controller->deleteMethodChunk($uri[2]);
                    } else {
                        http_response_code(404);
                    }
                    break;
                case 'OPTIONS':
                    http_response_code(200);
                    break;
                default:
                    http_response_code(404);
                    break;
            }
            break;
        case 'criterion':
            $controller = new CriterionController();
            switch($method) {
                case 'GET':
                    if(isset($uri[2])) {
                        $controller->getCriterion($uri[2]);
                    } else {
                        $controller->getAllCriterion(); #GET /criterion
                    }
                    break;
                case 'DELETE':
                    if(isset($uri[2])) {
                        if(isset($uri[3])){
                            if($uri[3] == 'values' && isset($uri[4])) {
                                $controller->deleteCriterionValue($uri[2], $uri[4]); #DELETE /criterion/:id/values/:id
                            } else {
                                http_response_code(404);
                            }
                        } else {
                            $controller->deleteCriterion($uri[2]); #DELETE /criterion/:id
                        }
                    } else {
                        http_response_code(404);
                    }
                    break;
                case 'PUT':
                    if(isset($uri[2])) {
                        if(isset($uri[3])) {
                            if($uri[3] == 'values' && isset($uri[4])) {
                                $controller->updateCriterionValue($uri[2], $uri[4]); #PUT /criterion/:id/values/:id
                            } else {
                                http_response_code(404);
                            }
                        } else {
                            $controller->updateCriterion($uri[2]); #PUT /criterion/:id
                        }
                    } else {
                        http_response_code(404);
                    }
                    break;
                case 'POST':
                    if(isset($uri[2])) {
                        if(isset($uri[3]) && $uri[3] == 'values') {
                            $controller->addNewCriterionValue($uri[2]); #POST /criterion/:id/values
                        } else {
                            http_response_code(404);
                        }
                    } else {
                        $controller->addNewCriterion(); #POST /criterion
                    }
                    break;
                case 'OPTIONS':
                    http_response_code(200);
                    break;
                default:
                    http_response_code(404);
                    break;
            }
            break;
        case 'goal':
            $controller = new GoalController();
            switch($method) {
                case 'GET':
                    $controller->getAllGoals(); # GET /goal
                    break;
                case 'POST':
                    $controller->addNewGoal(); #POST /goal
                    break;
                case 'OPTIONS':
                    http_response_code(200);
                    break;
                default:
                    http_response_code(404);
                    break;
            }
            break;
        case 'strategy':
            $controller = new StrategyController();
            switch($method) {
                case 'GET':
                    if(isset($uri[2]) && $uri[2] == 'maps'){
                        $controller->getAllStrategieswithMaps(); #GET /strategy/maps
                    }else{
                        $controller->getAllStrategies(); #GET /strategy
                    }
                    break;
                case 'POST':
                    $controller->addNewStrategy(); #POST /strategy
                    break;
                case 'DELETE':
                    if(isset($uri[2])) {
                        $controller->deleteStrategyfromMap($uri[2]); #DELETE /strategy/:id
                    } else {
                        http_response_code(404);
                    }
                    break;
                case 'PUT':
                    $controller->updateStrategy($uri[2]); #PUT /strategy/:id                       
                    break; 
                case 'OPTIONS':
                    http_response_code(200);
                    break;
                default:
                    http_response_code(404);
                    break;
                }
                break;

        case 'maps':
            $controller = new MapController();
            switch($method) {
                case 'GET':
                    if(isset($uri[3]) && $uri[3] == 'goals') {
                        $controller->getMapWithGoals($uri[2]); #GET /maps/:id/goals
                    }else if(isset($uri[3]) && $uri[3] == 'strategies'){
                        $controller->getMapStrategies($uri[2]);
                    }else if(isset($uri[2])) {
                        $controller->getMap($uri[2]); #GET /maps/:id
                    } else {
                        $controller->getAllMaps(); #GET /maps
                    }
                    break;
                case 'POST':
                    $controller->addNewMap(); #POST /maps
                    break;
                case 'PUT':
                    $controller->updateMap($uri[2]); #PUT /maps/:id                       
                    break;
                case 'DELETE':
                    if(isset($uri[2])) {
                        $controller->deleteMap($uri[2]); #DELETE /maps/:id
                    } else {
                        http_response_code(404);
                    }
                    break;    
                case 'OPTIONS':
                    http_response_code(200);
                    break;
                default:
                    http_response_code(404);
                    break;
                
            }
            break;
        case 'repository':
            $controller = new RepositoryController();
            switch ($method) {
                case 'GET':
                    if (isset($uri[2])) { # GET /repository/:id
                        $controller->getRepository($uri[2]);
                    } else {
                        $controller->getAllRepositories(); # GET /repository
                    }
                    break;
                case 'POST':
                    $controller->addNewRepository(); # POST /repository
                    break;
                case 'PUT':
                    if (isset($uri[2])){
                        $controller->updateRepository($uri[2]); # PUT /repository/:id
                    }
                    break;
                case 'DELETE':
                    if (isset($uri[2])) {
                        $controller->deleteRepository($uri[2]); # DELETE /repository/:id
                    }
                    break;
                default:
                    http_response_code(404);
                    break;
            }
            break;
        default:
            http_response_code(404);
            break;
    }
    exit;
?>