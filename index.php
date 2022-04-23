<?php
    $uri = explode('/', parse_url($_SERVER['PATH_INFO'], PHP_URL_PATH));
	$method = $_SERVER['REQUEST_METHOD'];
    
    echo("Hello SOPCOM!");
    http_response_code(200);
?>