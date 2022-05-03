<?php

include $_SERVER['DOCUMENT_ROOT'] . '/SOPCOM/models/MethodChunkModel.php';
require $_SERVER['DOCUMENT_ROOT'] . '/SOPCOM/views/MethodChunkView.php';

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
     *     path="/index.php/method-chunk/{methodChunk}", 
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
            echo json_encode($response);
        } else {
            echo json_encode(Array("error" => "No method chunk found with id $id."));
            http_response_code(404);
        }
    }

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

    public function addNewMethodChunk() {
        $body = json_decode(file_get_contents('php://input'), true);
        if(isset($body['id']) && isset($body['name']) && isset($body['activity']) && $body['intention']) {
            $result = $this->MethodChunkModel->addNewMethodChunk($body['id'], $body['name'], $body['description'], $body['activity'], $body['intention']);
            if(isset($body['tools'])) $this->MethodChunkModel->addNewMethodChunkTools($body['id'], $body['tools']);
        } else {
            $result = Array("error" => "Missing required data");
            http_response_code(400);
        }
        header("Content-Type: application/json");
        echo json_encode($result);
    }
}

?>