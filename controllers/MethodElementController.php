<?php

include $_SERVER['DOCUMENT_ROOT'] . '/SOPCOM/models/MethodElementModel.php';
require $_SERVER['DOCUMENT_ROOT'] . '/SOPCOM/views/MethodElementView.php';

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
     * @OA\Get(
     *     path="/api_v1/index.php/method-element/relations/types", 
     *     tags={"Method elements"},
     *     summary="List all method element relation types",
     *     description="List all method element relation types",
     *     operationId="getAllMethodElementRelationTypes",
     *     @OA\Response(response="200", description="OK"),
     * )
    */
    public function getAllMethodElementRelationTypes() {
        $result = $this->MethodElementModel->getAllMethodElementRelationTypes();
        http_response_code(200);
        header("Content-Type: application/json");
        echo json_encode($result);
    }
    
    /**
     * @OA\POST(
     *     path="/api_v1/index.php/method-element", 
     *     tags={"Method elements"},
     *     summary="Add new method element",
     *     description="Add new method element",
     *     operationId="AddNewMethodELement",
     *     @OA\Response(response="202", description="Created"),
     * )
    */
    public function addNewMethodElement() {
        $body = json_decode(file_get_contents('php://input'), true);
        if(isset($body['id']) && isset($body['name']) && isset($body['abstract']) && isset($body['type']) && $body['type'] <= 4 && $body['type'] >= 1) {
            $id = $this->MethodElementModel->addNewMethodElement($body['id'], $body['name'], $body['abstract'], $body['description'], $body['figure'], $body['type']);
            if(isset($body['me_struct_rel'])) {
                $this->MethodElementModel->addMethodElementMeStructRel($id, $body['me_struct_rel']);
            }
            if($body['type'] == 3 && isset($body['activity_rel'])) {
                $this->MethodElementModel->addMethodElementActivityRel($id, $body['activity_rel']);
            }
            if($body['type'] == 2 && isset($body['artefact_rel'])) {
                $this->MethodElementModel->addMethodElementArtefactRel($id, $body['artefact_rel']);
            }
            $result = $id;
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
        $result = $this->MethodElementModel->updateMethodElement($id, $body['name'], $body['abstract'], $body['description'], $body['figure']);
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