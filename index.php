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
                        $controller->getMethodElement($uri[2]); # /method-element/:id
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