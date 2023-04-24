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
            $result = $this->StrategyModel->addNewStrategy($body['id'], $body['name'], $body['goal_tgt'], $body['goal_src'], $body['x'], $body['y']);
            echo(json_encode(Array('id' => $result)));
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


    
}