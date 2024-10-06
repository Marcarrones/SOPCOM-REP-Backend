<?php

include $_SERVER['DOCUMENT_ROOT'] . '/models/RepositoryModel.php';

class RepositoryController {

    private $RepositoryModel;

    function __construct()
    {
        $this->RepositoryModel = new Repository();
    }

    public function getRepositoryStatus() {
        $result = $this->RepositoryModel->getRepositoryStatus();
        http_response_code(200);
        header("Content-Type: application/json");
        echo json_encode($result);
    }

    public function getPublicRepositories() {
        $result = $this->RepositoryModel->getPublicRepositories();
        http_response_code(200);
        header("Content-Type: application/json");
        echo json_encode($result);
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
        $results = $this->RepositoryModel->getAllRepositories();
        foreach ($results as &$result) {
            if (isset($result['status'])) {
                $result['status'] = json_decode($result['status'], true);
            }
        }
        http_response_code(200);
        header("Content-Type: application/json");
        echo json_encode($results);
    } 

    public function getRepository($id) {
        $repository = $this->RepositoryModel->getRepository($id);
        if(count($repository) > 0) {
            $result = $repository[0];
            if (isset($result['status'])) {
                $result['status'] = json_decode($result['status'], true);
            }            
            http_response_code(200);
            header("Content-Type: application/json");
            echo json_encode($result);
        } else {
            header("Content-Type: application/json");
            http_response_code(404);
            echo json_encode(Array("error" => "No repository found with id $id."));
        }
    }

     /**
     * @OA\Delete(
     *     path="/index.php/repository/{repositoryId}", 
     *     tags={"Repository"},
     *     summary="Delete a repository by id",
     *     description="Delete a repository by id",
     *     operationId="deleteRepository",
     *     @OA\Parameter(
     *         description="Id of the repository to delete",
     *         in="path",
     *         name="repositoryId",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="string", value="Repository_1", summary="A string value."),
     *     ),
     *     @OA\Response(response="204"),
     * )
    */
    public function deleteRepository($id) {
        $result = $this->RepositoryModel->deleteRepository($id);
        if($result == 0) {
            http_response_code(204);    
        } else {
            $result = Array("code" => $result);
            http_response_code(400);
            header("Content-Type: application/json");
        }
        echo json_encode($result);
    }

    public function addNewRepository() {
        $body = json_decode(file_get_contents('php://input'), true);
        if(isset($body['id']) && isset($body['name'])) {
            $result = $this->RepositoryModel->addNewRepository($body['id'], $body['name'], $body['description'], $body['status']);
            echo(json_encode($this->RepositoryModel->getRepository($body['id'])[0]));
            http_response_code(201);
        } else {
            http_response_code(400);
            echo(json_encode(Array('error' => "Missing required data")));
        }
    }
    
    public function updateRepository($id) {
        try{
            $body = json_decode(file_get_contents('php://input'), true);
            $this->RepositoryModel->updateRepository($id, $body['name'], $body['description'], $body['status']);
            http_response_code(201);
            echo json_encode($this->RepositoryModel->getRepository($id)[0]);
        } catch (Exception $e) {
            http_response_code(404);
            header("Content-Type: application/json");
            echo json_encode(Array('error' => $e->getMessage()));
        }
    }
    
}