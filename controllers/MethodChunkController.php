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
            $response = $this->MethodChunkView->buildMethodChunk($methodChunk, $intention);
            http_response_code(200);
            echo json_encode($response);
        } else {
            echo json_encode(Array("error" => "No method chunk found with id $id."));
            http_response_code(404);
        }
    }
}

?>