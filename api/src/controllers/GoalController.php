<?php

include $_SERVER['DOCUMENT_ROOT'] . '/models/GoalModel.php';

class GoalController {

    private $GoalModel;

    function __construct()
    {
        $this->GoalModel = new Goal();
    }

    /**
     * @OA\Get(
     *     path="/index.php/goal", 
     *     tags={"Goals"},
     *     summary="List all goals",
     *     description="List all goals",
     *     operationId="getAllGoals",
     *     @OA\Response(response="200", description="OK",
     *          @OA\JsonContent(
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="result",
     *                      type="array",
     *                      @OA\Items(
     *                          type="object",
*                               @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                          ),
     *                          @OA\Property(
     *                              property="name",
     *                              type="string",
     *                          ),
     *                      )
     *                  )
     *              ),
     *              example={
                       {
                           "id": "Goal id",
                           "name": "Goal name"
                       },
                    },
     *          )
     *     ),
     * )
    */
    public function getAllGoals() {
        $result = $this->GoalModel->getAllGoals();
        http_response_code(200);
        header("Content-Type: application/json");
        echo json_encode($result);
    }

    /**
     * @OA\POST(
     *     path="/index.php/goal", 
     *     tags={"Goals"},
     *     summary="Add new goal",
     *     description="Add new goal",
     *     operationId="addNewGoal",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 example={"name": "Goal name"}
     *             )
     *         )
     *     ),
     *     @OA\Response(response="201", description="Created"),
     * )
    */
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