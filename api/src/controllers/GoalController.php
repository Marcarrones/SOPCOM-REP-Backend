<?php

include $_SERVER['DOCUMENT_ROOT'] . '/models/GoalModel.php';
include $_SERVER['DOCUMENT_ROOT'] . '/views/GoalView.php';


class GoalController {

    private $GoalModel;
    private $GoalView;

    function __construct()
    {
        $this->GoalModel = new Goal();
        $this->GoalView  = new GoalView();
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
     * @OA\Post(
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
        if(isset($body['name']) && isset($body['map']) && isset($body['x']) && isset($body['y'])) {
            $result = $this->GoalModel->addNewGoalWithCoord($body['name'], $body['map'], $body['x'], $body['y']);
            echo(json_encode(Array('id' => $result)));
            http_response_code(201);
        }else if(isset($body['name']) && isset($body['map'])) {
            $result = $this->GoalModel->addNewGoalWithMap($body['name'], $body['map']);
            echo(json_encode(Array('id' => $result)));
            http_response_code(201);
        }else if(isset($body['name'])){
            print($body['map']);
            $result = $this->GoalModel->addNewGoal($body['name']);
            echo(json_encode(Array('idd' => $result)));
            http_response_code(201);
        } else {
            http_response_code(400);
            echo(json_encode(Array('error' => "Missing name body parameter")));
        }
    }
<<<<<<< HEAD
<<<<<<< Updated upstream
=======
=======


>>>>>>> main

    public function getGoal($id) {
        $goal = $this->GoalModel->getGoal($id);
        if(count($goal) > 0) {
            $result = $this->GoalView->buildGoal($goal);
            http_response_code(200);
            header("Content-Type: application/json");
            echo json_encode($result);
        } else {
            header("Content-Type: application/json");
            http_response_code(404);
            echo json_encode(Array("error" => "No criterion found with id $id."));
        }
    }

<<<<<<< HEAD
=======



>>>>>>> main
    public function goalStrategies($name) {
        $result = $this->GoalModel->goalStrategies($name);
        http_response_code(200);
        header("Content-Type: application/json");
        echo json_encode($result);

    }

<<<<<<< HEAD
=======

>>>>>>> main
    public function updateGoal($id) {
        $body = json_decode(file_get_contents('php://input'), true);
        if($body['name'] == null){
            $result = $this->GoalModel->updatePos($id, $body['x'], $body['y']);
        }else{
            $result = $this->GoalModel->updateGoal($id, $body['name'], $body['x'], $body['y']);
        }


        if($result == 0) {
            http_response_code(201);
        } else {
            $result = Array("code" => $result);
            http_response_code(404);
            header("Content-Type: application/json");
            echo json_encode($result);
        }
    }

<<<<<<< HEAD
=======
  
>>>>>>> main
    public function deleteGoalfromMap($id) {
        $result = $this->GoalModel->deleteGoalfromMap($id);
        if($result == 0) {
            http_response_code(204);
        } else {
            $result = Array("code" => $result);
            http_response_code(400);
            header("Content-Type: application/json");
            echo json_encode($result);
        }
    }

<<<<<<< HEAD
=======



>>>>>>> main
    public function getGoalsWithoutMap() {
        $result = $this->GoalModel->getGoalsWithoutMap();
        http_response_code(200);
        header("Content-Type: application/json");
        echo json_encode($result);
    }
   
    
<<<<<<< HEAD
>>>>>>> Stashed changes
=======
>>>>>>> main
}