<?php

    require $_SERVER['DOCUMENT_ROOT'] . '/SOPCOM/models/Model.php';
    require $_SERVER['DOCUMENT_ROOT'] . '/SOPCOM/controllers/MethodElementController.php';
    require $_SERVER['DOCUMENT_ROOT'] . '/SOPCOM/controllers/CriterionController.php';
    require $_SERVER['DOCUMENT_ROOT'] . '/SOPCOM/controllers/GoalController.php';
    require $_SERVER['DOCUMENT_ROOT'] . '/SOPCOM/controllers/MethodChunkController.php';

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

                    }
                    break;
                case 'POST':
                    if(isset($ur[2])){

                    } else {
                        $controller->addNewMethodChunk(); #POST /method-chunk
                    }
                case 'DELETE':
                    if(isset($uri[2])) {
                        $controller->deleteMethodChunk($uri[2]);
                    } else {
                        http_response_code(404);
                    }
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

                    } else {
                        $controller->getAllCriterion(); #GET /criterion
                    }
                    break;
                case 'DELETE':
                    if(isset($uri[2])) {
                        $controller->deleteCriterion($uri[2]); #DELETE /criterion/:id
                    } else {
                        http_response_code(404);
                    }
                    break;
                case 'POST':
                    if(isset($uri[2])) {

                    } else {
                        $controller->addNewCriterion(); #POST /criterion
                    }
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
                default:
                    http_response_code(404);
                    break;
            }
            break;
        default:
            http_response_code(404);
            break;
    }
    header("Content-Type: application/json");
    exit;
?>