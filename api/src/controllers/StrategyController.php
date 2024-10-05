<?php

include $_SERVER['DOCUMENT_ROOT'] . '/models/StrategyModel.php';

class StrategyController {

    private $StrategyModel;

    function __construct()
    {
        $this->StrategyModel = new Strategy();
    }
   
    public function addNewStrategy() {
        $body = json_decode(file_get_contents('php://input'), true);
        if(isset($body['id']) && isset($body['name']) && isset($body['goal_tgt']) && isset($body['goal_src']) && isset($body['x']) && isset($body['y'])) {
            $result = $this->StrategyModel->addNewStrategy($body['id'], $body['x'], $body['y'], $body['name'], $body['goal_tgt'], $body['goal_src']);
            $result = $this->StrategyModel->getStrategy($body['id']);
            echo(json_encode($result[0]));
            http_response_code(201);
        } else {
            http_response_code(400);
            echo(json_encode(Array('error' => "Missing parameter")));
        }
    }

    public function getAllStrategies() {
        $result = $this->StrategyModel->getAllStrategies();
        http_response_code(200);
        header("Content-Type: application/json");
        echo json_encode($result);
    }

    public function getAllStrategieswithMaps() {
        $result = $this->StrategyModel->getAllStrategieswithMaps();
        http_response_code(200);
        header("Content-Type: application/json");
        echo json_encode($result);
    }

    public function updateStrategy($id) {
        $body = json_decode(file_get_contents('php://input'), true);
            
        if(!isset($body['name'])){
            $result = $this->StrategyModel->updateStrategyPos($id, $body['x'], $body['y']);
        }else{
            $result = $this->StrategyModel->updateStrategy($id, $body['name']);
        }
        
        if(true) {
            http_response_code(201);
            echo json_encode($this->StrategyModel->getStrategy($id));
        } else {
            $result = Array("code" => $result);
            http_response_code(404);
            header("Content-Type: application/json");
            echo json_encode($result);
        }
    }

    public function deleteStrategyfromMap($id) {
        $result = $this->StrategyModel->deleteStrategyfromMap($id);
        if($result == 0) {
            http_response_code(204);
        } else {
            $result = Array("code" => $result);
            http_response_code(400);
            header("Content-Type: application/json");
            echo json_encode($result);
        }
    }

}