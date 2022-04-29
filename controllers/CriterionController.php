<?php

include $_SERVER['DOCUMENT_ROOT'] . '/SOPCOM/models/CriterionModel.php';
include $_SERVER['DOCUMENT_ROOT'] . '/SOPCOM/views/CriterionView.php';

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
     *     path="/api_v1/index.php/criterion", 
     *     tags={"Criterions"},
     *     summary="List all criterions and its values",
     *     description="List all criterions and its values",
     *     operationId="getAllCriterions",
     *     @OA\Response(response="200", description="OK"),
     * )
    */
    public function getAllCriterion() {
        $criterions = $this->CriterionModel->getAllCriterions();
        $result = $this->CriterionView->buildCriterionsList($criterions);
        http_response_code(200);
        header("Content-Type: application/json");
        echo json_encode($result);
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
     *         @OA\Schema(type="int"),
     *         @OA\Examples(example="int", value="1", summary="A integer value."),
     *     ),
     *     @OA\Response(response="204", description="No content"),
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

    public function addNewCriterion() {
        $body = json_decode(file_get_contents('php://input'), true);
        if(isset($body['name']) && isset($body['values']) && count($body['values']) > 2) {
            $id = $this->CriterionModel->addNewCriterion($body['name']);
            foreach($body['values'] as $value) {
                $this->CriterionModel->addNewValueToCriterion($id, $value['name']);
            }
        } else if(!isset($body['name'])) {
            http_response_code(400);
            echo(json_encode(Array('error' => "Missing name body parameter")));
        } else if(!isset($body['values']) || count($body['values']) < 2) {
            http_response_code(400);
            echo(json_encode(Array('error' => "A criterion must have at least 2 values")));
        }
    }

}

?>