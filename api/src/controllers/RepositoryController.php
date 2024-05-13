<?php

include $_SERVER['DOCUMENT_ROOT'] . '/models/RepositoryModel.php';

class RepositoryController {

    private $RepositoryModel;

    function __construct()
    {
        $this->RepositoryModel = new Repository();
    }

    /**
     * @OA\Get(
     *     path="/index.php/repository", 
     *     tags={"Repository"},
     *     summary="List all repositories",
     *     description="List all repositories",
     *     operationId="getAllRepositories",
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
     *          )
     *     ),
     * )
    */
    public function getAllRepositories() {
        $result = $this->RepositoryModel->getAllRepositories();
        http_response_code(200);
        header("Content-Type: application/json");
        echo json_encode($result);
    } 

    public function getRepository($id) {
        $repository = $this->RepositoryModel->getRepository($id);
        if(count($repository) > 0) {
            $result = $repository[0];
            http_response_code(200);
            header("Content-Type: application/json");
            echo json_encode($result);
        } else {
            header("Content-Type: application/json");
            http_response_code(404);
            echo json_encode(Array("error" => "No criterion found with id $id."));
        }
    }

    public function deleteRepository($id) {
        $result = $this->RepositoryModel->deleteRepository($id);
        if($result == 0) {
            http_response_code(204);
        } else {
            $result = Array("code" => $result);
            http_response_code(400);
            header("Content-Type: application/json");
            echo json_encode($result);
        }
    }

    public function addNewRepository() {
        $body = json_decode(file_get_contents('php://input'), true);
        if(isset($body['id']) && isset($body['name'])) {
            $result = $this->RepositoryModel->addNewRepository($body['id'], $body['name'], $body['description'], $body['status']);
            echo(json_encode(Array('id' => $result)));
            http_response_code(201);
        } else {
            http_response_code(400);
            echo(json_encode(Array('error' => "Missing required data")));
        }
    }
    
    public function updateRepository($id) {
        $body = json_decode(file_get_contents('php://input'), true);
        $result = $this->RepositoryModel->updateRepository($id, $body['name'], $body['description'], $body['status']);
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