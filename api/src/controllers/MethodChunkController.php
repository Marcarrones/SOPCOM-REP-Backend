<?php

include $_SERVER['DOCUMENT_ROOT'] . '/models/MethodChunkModel.php';
require $_SERVER['DOCUMENT_ROOT'] . '/views/MethodChunkView.php';

/**
 * @OA\OpenApi(
 *     @OA\Server(
 *         url="localhost",
 *         description="API server"
 *     ),
 *     @OA\Info(
 *         version="1.0.0",
 *         title="SOPCOM",
 *         description="SOPCOM REST API",
 *     ),
 * )
 */
class MethodChunkController {

    private $MethodChunkModel;
    private $MethodChunkView;

    function __construct()
    {
        $this->MethodChunkModel = new MethodChunk();
        $this->MethodChunkView  = new MethodChunkView();
    }

    /**
     * @OA\Get(
     *     path="/index.php/method-chunk/{methodChunkId}", 
     *     tags={"Method chunks"},
     *     summary="Find a method chunk by id",
     *     description="Find a method chunk by id",
     *     operationId="getMethodChunk",
     *     @OA\Parameter(
     *         description="Id of the method chunk",
     *         in="path",
     *         name="methodChunkId",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="string", value="Chu-ReqEli-01", summary="A string value."),
     *     ),
     *     @OA\Response(response="200", description="OK"),
     *     @OA\Response(response="404", description="Not found")
     * )
    */
    public function getMethodChunk($id) {
        $methodChunk = $this->MethodChunkModel->getMethodChunk($id);
        if(count($methodChunk) > 0) {
            $intention = $this->MethodChunkModel->getMethodChunkIntention($id);
            $tools = $this->MethodChunkModel->getMethodChunkTools($id);
            $artefacts = $this->MethodChunkModel->getMethodChunkArtefacts($id);
            $activity = $this->MethodChunkModel->getMethodChunkActivity($id);
            $roles = $this->MethodChunkModel->getMethodChunkRoles($id);
            $contextCriteria = $this->MethodChunkModel->getMethodChunkContextCriteria($id);
            $relations = $this->MethodChunkModel->getMethodChunkRelations($id);
            $response = $this->MethodChunkView->buildMethodChunk($methodChunk, $intention, $tools, $artefacts, $activity, $roles, $contextCriteria, $relations);
            http_response_code(200);
            header("Content-Type: application/json");
            echo json_encode($response);
        } else {
            echo json_encode(Array("error" => "No method chunk found with id $id."));
            http_response_code(404);
        }
    }

    /**
     * @OA\Delete(
     *     path="/index.php/method-chunk/{methodChunkId}", 
     *     tags={"Method chunks"},
     *     summary="Delete a method chunk by id",
     *     description="Delete a method chunk by id",
     *     operationId="deleteMethodChunk",
     *     @OA\Parameter(
     *         description="Id of the method chunk",
     *         in="path",
     *         name="methodChunkId",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="string", value="Chu-ReqEli-01", summary="A string value."),
     *     ),
     *     @OA\Response(response="204", description="No content"),
     * )
    */
    public function deleteMethodChunk($id) {
        $result = $this->MethodChunkModel->deleteMethodChunk($id);
        if($result == 0) {
            http_response_code(204);
        } else {
            $result = Array("code" => $result);
            http_response_code(400);
            header("Content-Type: application/json");
            echo json_encode($result);
        }
    }

    /**
     * @OA\Post(
     *     path="/index.php/method-chunk", 
     *     tags={"Method chunks"},
     *     summary="Add new method chunk",
     *     description="Add new method chunk",
     *     operationId="AddNewMethodChunk",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="id",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="activity",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="intention",
     *                     type="integer",
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(response="202", description="Created"),
     * )
    */
    public function addNewMethodChunk() {
        $body = json_decode(file_get_contents('php://input'), true);
        if(isset($body['id']) && isset($body['name']) && isset($body['activity']) && $body['intention']) {
            $result = $this->MethodChunkModel->addNewMethodChunk($body['id'], $body['name'], $body['description'], $body['activity'], $body['intention']);
            if(isset($body['tools'])) $this->MethodChunkModel->addNewMethodChunkTools($body['id'], $body['tools']);
            if(isset($body['consumedArtefacts'])) $this->MethodChunkModel->addNewMethodChunkConsumedArtefacts($body['id'], $body['consumedArtefacts']);
            if(isset($body['producedArtefacts'])) $this->MethodChunkModel->addNewMethodChunkProducedArtefacts($body['id'], $body['producedArtefacts']);
            if(isset($body['roles'])) $this->MethodChunkModel->addNewMethodChunkRoles($body['id'], $body['roles']);
            if(isset($body['contextCriteria'])) $this->MethodChunkModel->addMethodChunkContextCriteria($body['id'], $body['contextCriteria']);
            http_response_code(201);
        } else {
            $result = Array("error" => "Missing required data");
            http_response_code(400);
        }
        header("Content-Type: application/json");
        echo json_encode($result);
    }

    /**
     * @OA\Put(
     *     path="/index.php/method-chunk/{methodChunkId}", 
     *     tags={"Method chunks"},
     *     summary="Update a method chunk by id",
     *     description="Update a method chunk by id",
     *     operationId="updateMethodChunk",
     *     @OA\Parameter(
     *         description="Id of the method chunk",
     *         in="path",
     *         name="methodChunkId",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="string", value="Chu-ReqEli-01", summary="A string value."),
     *     ),
     *     @OA\Response(response="201", description="Created"),
     *     @OA\Response(response="404", description="Not found")
     * )
    */
    public function updateMethodChunk($id) {
        $body = json_decode(file_get_contents('php://input'), true);
        $result = $this->MethodChunkModel->updateMethodChunk($id, $body['name'], $body['description'], $body['activity'], $body['intention']);
        if($result == 0) {
            if(isset($body['tools'])) $this->MethodChunkModel->updateMethodChunkTools($id, $body['tools']);
            if(isset($body['consumedArtefacts'])) $this->MethodChunkModel->updateMethodChunkConsumedArtefacts($id, $body['consumedArtefacts']);
            if(isset($body['producedArtefacts'])) $this->MethodChunkModel->updateMethodChunkProducedArtefacts($id, $body['producedArtefacts']);
            if(isset($body['roles'])) $this->MethodChunkModel->updateMethodChunkRoles($id, $body['roles']);
            if(isset($body['contextCriteria'])) $this->MethodChunkModel->updateMethodChunkContextCriteria($id, $body['contextCriteria']);
            http_response_code(201);
        } else {
            $result = Array("code" => $result);
            http_response_code(404);
            header("Content-Type: application/json");
            echo json_encode($result);
        }
    }

    /**
     * @OA\Get(
     *     path="/index.php/method-chunk", 
     *     tags={"Method chunks"},
     *     summary="List all method chunks",
     *     description="List all method chunks",
     *     operationId="getAllMethodChunks",
     *     @OA\Response(response="200", description="OK"),
     * )
    */
    public function getAllMethodChunk() {
        $result = $this->MethodChunkModel->getAllMethodChunk();
        http_response_code(200);
        header("Content-Type: application/json");
        echo json_encode($result);
    }
}

?>