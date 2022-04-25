<?php

include $_SERVER['DOCUMENT_ROOT'] . '/SOPCOM/models/MethodElementModel.php';

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
class MethodElementController {

    private $MethodElementModel;

    function __construct()
    {
        $this->MethodElementModel = new MethodElement();
    }

    /**
     * @OA\Get(
     *     path="/index.php/method-element/{methodElementId}", 
     *     tags={"Method elements"},
     *     summary="Find a method element by id",
     *     description="Find a method element by id",
     *     operationId="getMethodElement",
     *     @OA\Parameter(
     *         description="Id of the method element",
     *         in="path",
     *         name="methodElementId",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="string", value="Chu-ReqEli-01", summary="A string value."),
     *     ),
     *     @OA\Response(response="200", description="OK"),
     *     @OA\Response(response="404", description="Not found")
     * )
    */
    public function getMethodElement($id) {
        $methodElement = $this->MethodElementModel->getMethodElementById($id);
        //$relationsTo = $this->MethodElementModel->getMethodElementToRelations($id);
        //$relationsFrom = $this->MethodElementModel->getMethodElementFromRelations($id);
        header("Content-Type: application/json");
        echo json_encode($methodElement);
    }
}

?>