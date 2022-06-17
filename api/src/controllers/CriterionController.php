<?php

include $_SERVER['DOCUMENT_ROOT'] . '/models/CriterionModel.php';
include $_SERVER['DOCUMENT_ROOT'] . '/views/CriterionView.php';

class CriterionController {

    private $CriterionModel;
    private $CriterionView;

    function __construct()
    {
        $this->CriterionModel = new Criterion();
        $this->CriterionView  = new CriterionView();
    }

    /**
     * @OA\Get(
     *     path="/index.php/criterion", 
     *     tags={"Criterions"},
     *     summary="List all criterions and its values",
     *     description="List all criterions and its values",
     *     operationId="getAllCriterions",
     *     @OA\Response(
     *          response="200", 
     *          description="OK",
     *          @OA\JsonContent(
     *              @OA\Schema(
     *                  type="array",
     *                  @OA\Items(
     *                      @OA\Property(
     *                          property="name",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="values",
     *                          type="array",
     *                          @OA\Items(
     *                              type="string"
     *                          )
     *                      ),
     *                  )
     *                  
     *              ),
     *              example={
     *                  {
                            "name": "Stakeholders expertise",
                            "values": {
                                "High",
                                "Medium",
                                "Basic"
                            }
                        },
                        {
                            "name": "Stakeholders other things",
                            "values": {
                                "High",
                                "Medium",
                                "Basic"
                            }
                        }
                    }
     *          )
     *     ),
     * )
    */
    public function getAllCriterion() {
        $criterions = $this->CriterionModel->getAllCriterions();
        $result = $this->CriterionView->buildCriteriaList($criterions);
        http_response_code(200);
        header("Content-Type: application/json");
        echo json_encode($result);
    }

    /**
     * @OA\Get(
     *     path="/index.php/criterion/{criterionId}", 
     *     tags={"Criterions"},
     *     summary="Get criterion by id",
     *     description="Get criterion by id",
     *     operationId="getCriterion",
     *     @OA\Parameter(
     *         description="Id of the criterion",
     *         in="path",
     *         name="criterionId",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         @OA\Examples(example="string", value="1", summary="An integer value."),
     *     ),
     *     @OA\Response(response="200", description="OK"),
     *     @OA\Response(response="404", description="Not found")
     * )
    */
    public function getCriterion($id) {
        $criterion = $this->CriterionModel->getCriterion($id);
        if(count($criterion) > 0) {
            $values = $this->CriterionModel->getCriterionValues($id);
            $result = $this->CriterionView->buildCriterion($criterion, $values);
            http_response_code(200);
            header("Content-Type: application/json");
            echo json_encode($result);
        } else {
            header("Content-Type: application/json");
            http_response_code(404);
            echo json_encode(Array("error" => "No criterion found with id $id."));
        }
    }

    /**
     * @OA\Delete(
     *     path="/index.php/criterion/{criterionId}", 
     *     tags={"Criterions"},
     *     summary="Delete a criterion by id",
     *     description="Delete a criterion by id",
     *     operationId="deleteCriterion",
     *     @OA\Parameter(
     *         description="Id of the criterion",
     *         in="path",
     *         name="criterionId",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         @OA\Examples(example="integer", value="1", summary="An integer value."),
     *     ),
     *     @OA\Response(response="204", description="No content"),
     *     @OA\Response(response="400", description="Bad request"),
     * )
    */
    public function deleteCriterion($id) {
        $result = $this->CriterionModel->deleteCriterion($id);
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
     *     path="/index.php/criterion", 
     *     tags={"Criterions"},
     *     summary="Add new criterion",
     *     description="Add new criterion",
     *     operationId="AddNewCriterion",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="values",
     *                     type="array",
     *                     @OA\Items(
     *                          type="string"
     *                     )
     *                 ),
     *             ),
     *             example={
                        "name": "Stakeholders expertise",
                        "values": {
                            "High",
                            "Medium",
                            "Basic"
                        }
                    }
     *         )
     *     ),
     *     @OA\Response(response="201", description="Created"),
     * )
    */
    public function addNewCriterion() {
        $body = json_decode(file_get_contents('php://input'), true);
        if(isset($body['name']) && isset($body['values']) && count($body['values']) >= 2) {
            $id = $this->CriterionModel->addNewCriterion($body['name']);
            foreach($body['values'] as $value) {
                $this->CriterionModel->addNewValueToCriterion($id, $value);
            }
            http_response_code(201);
            header("Content-Type: application/json");
            echo(json_encode(Array('id' => $id)));
        } else if(!isset($body['name'])) {
            http_response_code(400);
            echo(json_encode(Array('error' => "Missing name body parameter")));
        } else if(!isset($body['values']) || count($body['values']) < 2) {
            http_response_code(400);
            echo(json_encode(Array('error' => "A criterion must have at least 2 values")));
        } else {
            http_response_code(400);
            echo(json_encode(Array('error' => "Missing data")));
        }
    }

    /**
     * @OA\Put(
     *     path="/index.php/criterion/{criterionId}", 
     *     tags={"Criterions"},
     *     summary="Update criterion",
     *     description="Update criterion",
     *     operationId="UpdateCriterion",
     *     @OA\Parameter(
     *         description="Id of the criterion",
     *         in="path",
     *         name="criterionId",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         @OA\Examples(example="integer", value="1", summary="An integer value."),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *             ),
     *             example={
                        "name": "Large"
                    }
     *         )
     *     ),
     *     @OA\Response(response="201", description="Created"),
     *     @OA\Response(response="404", description="Not found")
     * )
    */
    public function updateCriterion($id) {
        $body = json_decode(file_get_contents('php://input'), true);
        $result = $this->CriterionModel->updateCriterion($id, $body['name']);
        if($result == 0) {
            http_response_code(201);
        } else {
            $result = Array("code" => $result);
            http_response_code(404);
            header("Content-Type: application/json");
            echo json_encode($result);
        }
    }

    /**
     * @OA\Post(
     *     path="/index.php/criterion/{criterionId}/values", 
     *     tags={"Criterions"},
     *     summary="Add new criterion value",
     *     description="Add new criterion value",
     *     operationId="AddNewCriterionValue",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *             ),
     *             example={
                        "name": "Large"
                    }
     *         )
     *     ),
     *     @OA\Response(response="201", description="Created"),
     * )
    */
    public function addNewCriterionValue($id) {
        $body = json_decode(file_get_contents('php://input'), true);
        if(isset($body['name'])) {
            $id = $this->CriterionModel->addNewValueToCriterion($id, $body['name']);
            http_response_code(201);
            header("Content-Type: application/json");
            echo(json_encode(Array('id' => $id)));
        } else {
            $result = Array("error" => "Missing required data");
            http_response_code(400);
            echo json_encode($result);
        }
    }

    /**
     * @OA\Put(
     *     path="/index.php/criterion/{criterionId}/values/{valueId}", 
     *     tags={"Criterions"},
     *     summary="Update criterion value",
     *     description="Update criterion value",
     *     operationId="updateCriterionValue",
     *     @OA\Parameter(
     *         description="Id of the criterion",
     *         in="path",
     *         name="criterionId",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         @OA\Examples(example="integer", value="1", summary="An integer value."),
     *     ),
     *     @OA\Parameter(
     *         description="Id of the value",
     *         in="path",
     *         name="valueId",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         @OA\Examples(example="integer", value="1", summary="An integer value."),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *             ),
     *             example={
                        "name": "Large"
                    }
     *         )
     *     ),
     *     @OA\Response(response="201", description="Created"),
     *     @OA\Response(response="404", description="Not found")
     * )
    */
    public function updateCriterionValue($idC, $idV) {
        $body = json_decode(file_get_contents('php://input'), true);
        $result = $this->CriterionModel->updateCriterionValue($idV, $body['name']);
        if($result == 0) {
            http_response_code(201);
        } else {
            $result = Array("code" => $result);
            http_response_code(404);
            header("Content-Type: application/json");
            echo json_encode($result);
        }
    }

    /**
     * @OA\Delete(
     *     path="/index.php/criterion/{criterionId}/values/{valueId}", 
     *     tags={"Criterions"},
     *     summary="Delete a value by id",
     *     description="Delete a value by id",
     *     operationId="deleteCriterionValue",
     *     @OA\Parameter(
     *         description="Id of the criterion",
     *         in="path",
     *         name="criterionId",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         @OA\Examples(example="integer", value="1", summary="An integer value."),
     *     ),
     *     @OA\Parameter(
     *         description="Id of the value",
     *         in="path",
     *         name="valueId",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         @OA\Examples(example="integer", value="1", summary="An integer value."),
     *     ),
     *     @OA\Response(response="204", description="No content"),
     *     @OA\Response(response="400", description="Bad request"),
     * )
    */
    public function deleteCriterionValue($idC, $idV) {
        $result = $this->CriterionModel->deleteCriterionValue($idC, $idV);
        if($result == 0) {
            http_response_code(204);
        } else {
            $result = Array("error" => $result);
            http_response_code(400);
            header("Content-Type: application/json");
            echo json_encode($result);
        }
    }

}

?>