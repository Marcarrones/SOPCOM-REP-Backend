<?php

include $_SERVER['DOCUMENT_ROOT'] . '/models/MapModel.php';

class MapController {

    private $MapModel;

    function __construct()
    {
        $this->MapModel = new Map();
    }

    public function getAllMaps() {
        $result = $this->MapModel->getAllMaps();
        http_response_code(200);
        header("Content-Type: application/json");
        echo json_encode($result);
    }



    public function addNewMap() {
        $body = json_decode(file_get_contents('php://input'), true);
        if(isset($body['id']) && isset($body['name'])) {
            $result = $this->MapModel->addNewMap($body['id'], $body['name']);
            //echo(json_encode(Array('id' => $result)));
            http_response_code(201);
        } else {
            http_response_code(400);
            echo(json_encode(Array('error' => "Missing required data")));
        }
    }
    
}