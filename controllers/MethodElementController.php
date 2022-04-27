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
    private $MethodElementView;

    function __construct()
    {
        $this->MethodElementModel = new MethodElement();
        $this->MethodElementView  = new MethodElementView();
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
        if(count($methodElement) > 0) {
            $relationsTo = $this->MethodElementModel->getMethodElementToRelations($id);
            //$relationsFrom = $this->MethodElementModel->getMethodElementFromRelations($id);
            $relationsFrom = [];
            $response = $this->MethodElementView->buildMethodElement($methodElement, $relationsTo, $relationsFrom);
            http_response_code(200);
            echo json_encode($response);
        } else {
            echo json_encode(Array("error" => "No method element found with id $id."));
            http_response_code(404);
        }
    }

    /**
     * @OA\Get(
     *     path="/api_v1/index.php/method-element", 
     *     tags={"Method elements"},
     *     summary="List all method elements",
     *     description="List all method elements",
     *     operationId="getAllMethodElement",
     *     @OA\Response(response="200", description="OK"),
     * )
    */
    public function getAllMethodElement() {
        $type = isset($_GET['type']) ? $_GET['type'] : null;
        $result = $this->MethodElementModel->getAllMethodElements($type);
        http_response_code(200);
        header("Content-Type: application/json");
        echo json_encode($result);
    }

    /**
     * @OA\Get(
     *     path="/api_v1/index.php/method-element/types", 
     *     tags={"Method elements"},
     *     summary="List all method element types",
     *     description="List all method element types",
     *     operationId="getAllMethodElementTypes",
     *     @OA\Response(response="200", description="OK"),
     * )
    */
    public function getAllMethodElementTypes() {
        $result = $this->MethodElementModel->getAllMethodElementTypes();
        http_response_code(200);
        header("Content-Type: application/json");
        echo json_encode($result);
    }

    /**
     * @OA\Delete(
     *     path="/index.php/method-element/{methodElementId}", 
     *     tags={"Method elements"},
     *     summary="Delete a method element by id",
     *     description="Delete a method element by id",
     *     operationId="deleteMethodElement",
     *     @OA\Parameter(
     *         description="Id of the method element",
     *         in="path",
     *         name="methodElementId",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="string", value="Chu-ReqEli-01", summary="A string value."),
     *     ),
     *     @OA\Response(response="204", description="No content"),
     * )
    */
    public function deleteMethodElement($id) {
        $result = $this->MethodElementModel->deleteMethodElementById($id);
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
     * @OA\Put(
     *     path="/index.php/method-element/{methodElementId}", 
     *     tags={"Method elements"},
     *     summary="Update a method element by id",
     *     description="Update a method element by id",
     *     operationId="updateMethodElement",
     *     @OA\Parameter(
     *         description="Id of the method element",
     *         in="path",
     *         name="methodElementId",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="string", value="Chu-ReqEli-01", summary="A string value."),
     *     ),
     *     @OA\Response(response="201", description="Created"),
     *     @OA\Response(response="404", description="Not found")
     * )
    */
    public function updateMethodElement($id) {
        $body = json_decode(file_get_contents('php://input'), true);
        $result = $this->MethodElementModel->updateMethodElement($id, $body['name'], $body['abstract'], $body['description'], $body['figure'], $body['type']);
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

?>