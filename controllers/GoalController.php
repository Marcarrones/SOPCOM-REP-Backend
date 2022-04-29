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
}