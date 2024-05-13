<?php

require $_SERVER['DOCUMENT_ROOT'] . '/models/MethodElementModel.php';
require $_SERVER['DOCUMENT_ROOT'] . '/views/MethodElementView.php';

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
            $relationsFrom = $this->MethodElementModel->getMethodElementFromRelations($id);
            $response = $this->MethodElementView->buildMethodElement($methodElement, $relationsTo, $relationsFrom);
            header("Content-Type: application/json");
            http_response_code(200);
            echo json_encode($response);
        } else {
            header("Content-Type: application/json");
            echo json_encode(Array("error" => "No method element found with id $id."));
            http_response_code(404);
        }
    }

    /**
     * @OA\Get(
     *     path="/index.php/method-element", 
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
     *     path="/index.php/method-element/types", 
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
     *     path="/index.php/method-element/relations/types", 
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
     * @OA\Post(
     *     path="/index.php/method-element", 
     *     tags={"Method elements"},
     *     summary="Add new method element",
     *     description="Add new method element",
     *     operationId="AddNewMethodELement",
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
     *                     property="figure",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="type",
     *                     type="integer",
     *                 ),
     *                 @OA\Property(
     *                     property="me_struct_rel",
     *                     type="array",
     *                     @OA\Items(
     *                          type="object",
     *                          @OA\Property(
     *                              property="id",
     *                              type="string",
     *                          ),
     *                          @OA\Property(
     *                              property="rel",
     *                              type="integer",
     *                          ),
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="activity_rel",
     *                     type="array",
     *                     @OA\Items(
     *                          type="object",
     *                          @OA\Property(
     *                              property="id",
     *                              type="string",
     *                          ),
     *                          @OA\Property(
     *                              property="rel",
     *                              type="integer",
     *                          ),
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="artefact_rel",
     *                     type="array",
     *                     @OA\Items(
     *                          type="object",
     *                          @OA\Property(
     *                              property="id",
     *                              type="string",
     *                          ),
     *                          @OA\Property(
     *                              property="rel",
     *                              type="integer",
     *                          ),
     *                     )
     *                 ),
     *                 example={
                            "id": "Act-ElRq-03",
                            "name": "Elicit Requirements from survey",
                            "abstract": false,
                            "description": "Elaborate survey, select stakeholders to whom send the survey, obtain responses and extract requirements from responses.",
                            "figure": null,
                            "type": 3,
                            "me_struct_rel": {
                                {
                                    "id": "Act-ElRq-01",
                                    "rel": 1
                                },
                            },
                            "activity_rel": {},
                            "artefact_rel": {}
                        }
     *             )
     *         )
     *     ),
     *     @OA\Response(response="202", description="Created"),
     * )
    */
    public function addNewMethodElement() {
        $body = json_decode(file_get_contents('php://input'), true);
        if(isset($body['id']) && isset($body['name']) && isset($body['abstract']) && isset($body['type']) && $body['type'] <= 4 && $body['type'] >= 1) {
            $id = $this->MethodElementModel->addNewMethodElement($body['id'], $body['name'], $body['abstract'], $body['description'], $body['figure'], $body['type'], $body['repository']);
            if(!is_array($id)) {
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
                header("Content-Type: application/json");
                echo json_encode(Array('id' => $result));
            } else {
                http_response_code(200);
                header("Content-Type: application/json");
                echo json_encode(Array($id));
            }
        } else {
            $result = Array("error" => "Missing required data");
            http_response_code(400);
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
     *     @OA\Response(response="400", description="Not found")
     * )
    */
    public function updateMethodElement($id) {
        $body = json_decode(file_get_contents('php://input'), true);
        if($body['abstract'] == false && $this->MethodElementModel->checkIfHasSpecRels($id)) {
            $result = Array("error" => "Can not set abstract to false while being specialized");
            http_response_code(400);
            header("Content-Type: application/json");
            echo json_encode($result);
        } else {
            $result = $this->MethodElementModel->updateMethodElement($id, $body['name'], $body['abstract'], $body['description'], $body['figure'], $body['repository']);
            if($result == 0) {
                $res = $this->MethodElementModel->deleteAllRelationsFrom($id);
                if(isset($body['me_struct_rel'])) {
                    $this->MethodElementModel->updateMethodElementStructRel($id, $body['me_struct_rel']);
                }
                if($body['type'] == 3 && isset($body['activity_rel'])) {
                    $this->MethodElementModel->updateMethodElementActivityRel($id, $body['activity_rel']);
                }
                if($body['type'] == 2 && isset($body['artefact_rel'])) {
                    $this->MethodElementModel->updateMethodElementArtefactRel($id, $body['artefact_rel']);
                }
                http_response_code(201);
            } else {
                $result = Array("error" => $result);
                http_response_code(400);
                header("Content-Type: application/json");
                echo json_encode($result);
            }
        }
        
    }

    public function addMethodElementImage($id) {
        $result = $this->MethodElementModel->uploadImage($id);
        if(!is_array($result)) {
            http_response_code(201);
        } else {
            http_response_code(400);
            header("Content-Type: application/json");
            echo json_encode($result);
        }
    }
}

?>