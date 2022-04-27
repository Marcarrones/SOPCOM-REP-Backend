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
        if(isset($body['id']) && isset($body['name']) && isset($body['abstract']) && isset($body['type'])) {
            $result = $this->MethodElementModel->addNewMethodElement($body['id'], $body['name'], $body['abstract'], $body['description'], $body['figure'], $body['type']);
            http_response_code(202);
        } else {
            $result = Array("Error" => "Missing required data");
            http_response_code(400);
        }
        header("Content-Type: application/json");
        echo json_encode($result);
    }
}

?>