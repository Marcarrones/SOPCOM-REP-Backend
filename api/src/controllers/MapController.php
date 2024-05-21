<?php

include $_SERVER['DOCUMENT_ROOT'] . '/models/MapModel.php';
include $_SERVER['DOCUMENT_ROOT'] . '/views/MapView.php';


class MapController {

    private $MapModel;
    private $MapView;

    function __construct()
    {
        $this->MapModel = new Map();
        $this->MapView  = new MapView();
    }

    public function getAllMaps() {
        $repository = $_GET['repository'];
        $result = $this->MapModel->getAllMaps($repository);
        http_response_code(200);
        header("Content-Type: application/json");
        echo json_encode($result);
    }

    

    public function getMap($id) {
        $map = $this->MapModel->getMap($id);
        if(count($map) > 0) {
            $result = $this->MapView->buildMap($map);
            http_response_code(200);
            header("Content-Type: application/json");
            echo json_encode($result);
        } else {
            header("Content-Type: application/json");
            http_response_code(404);
            echo json_encode(Array("error" => "No criterion found with id $id."));
        }
    }


    public function getMapWithGoals($id) {
        $map = $this->MapModel->getMapWithGoals($id);
        if(count($map) > 0) {
            //$result = $this->MapView->buildGoalList($map);
            http_response_code(200);
            header("Content-Type: application/json");
            echo json_encode($map);
        } else {
            header("Content-Type: application/json");
            http_response_code(404);
            echo json_encode(Array("error" => "No criterion found with id $id."));
        }
    }


    public function getMapStrategies($id) {
        $map = $this->MapModel->getMapStrategies($id);
        if(count($map) > 0) {
            //$result = $this->MapView->buildGoalList($map);
            http_response_code(200);
            header("Content-Type: application/json");
            echo json_encode($map);
        } else {
            header("Content-Type: application/json");
            http_response_code(200);
            echo json_encode(0);
        }
    }



    public function deleteMap($id) {
        $result = $this->MapModel->deleteMap($id);
        if($result == 0) {
            http_response_code(204);
        } else {
            $result = Array("code" => $result);
            http_response_code(400);
            header("Content-Type: application/json");
            echo json_encode($result);
        }
    }



    public function addNewMap() {
        $body = json_decode(file_get_contents('php://input'), true);
        if(isset($body['id']) && isset($body['name'])) {
            $result = $this->MapModel->addNewMap($body['id'], $body['name'], $body['repository']);
            echo(json_encode(Array('id' => $result)));
            http_response_code(201);
        } else {
            http_response_code(400);
            echo(json_encode(Array('error' => "Missing required data")));
        }
    }
    


    public function updateMap($id) {
        $body = json_decode(file_get_contents('php://input'), true);
        $result = $this->MapModel->updateMap($body['name'], $id, $body['repository']);
        if($result == 0) {
            http_response_code(201);
        } else {
            $result = Array("code" => $result);
            http_response_code(404);
            header("Content-Type: application/json");
            echo json_encode($result);
        }
    }

    
}