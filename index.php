<?php
    require $_SERVER['DOCUMENT_ROOT'] . '/SOPCOM/controllers/MethodElementController.php';

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
                        } else {
                            $controller->getMethodElement($uri[2]); #GET /method-element/:id
                        }
                    } else {
                        $controller->getAllMethodElement(); #GET /method-element
                    }
                    break;
                case 'DELETE':
                    if(isset($uri[2])) {
                        $controller->deleteMethodElement($uri[2]); #DELETE /method-element/:id
                    } else {
                        http_response_code(404);
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
    header("Content-Type: application/json");
    exit;
?>