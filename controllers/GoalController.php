<?php

include $_SERVER['DOCUMENT_ROOT'] . '/SOPCOM/models/GoalModel.php';

class GoalController {

    private $GoalModel;

    function __construct()
    {
        $this->GoalModel = new Goal();
    }

    /**
     * @OA\Get(
     *     path="/api_v1/index.php/goal", 
     *     tags={"Goals"},
     *     summary="List all goals",
     *     description="List all goals",
     *     operationId="getAllGoals",
     *     @OA\Response(response="200", description="OK"),
     * )
    */
    public function getAllGoals() {
        $result = $this->GoalModel->getAllGoals();
        http_response_code(200);
        header("Content-Type: application/json");
        echo json_encode($result);
    }

    public function addNewGoal() {
        $body = json_decode(file_get_contents('php://input'), true);
        if(isset($body['name'])) {
            $this->GoalModel->addNewGoal($body['name']);
            http_response_code(201);
        } else {
            http_response_code(400);
            echo(json_encode(Array('error' => "Missing name body parameter")));
        }
    }
}