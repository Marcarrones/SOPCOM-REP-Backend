<?php
include $_SERVER['DOCUMENT_ROOT'] . '/models/ContextModel.php';

class ContextController {
    private Context $ContextModel;

    function __construct()
    {
        $this->ContextModel = new Context();
    }

public function getContext($id){
    $context = $this->ContextModel->getContext($id);
    if(count($context) > 0) {
        http_response_code(200);
        header("Content-Type: application/json");
        echo json_encode($context);
    } else {
        http_response_code(404);
        echo json_encode(
            array("message" => "Context not found.")
        );
    }

}

    public function getAllContexts() {
        $result = $this->ContextModel->getContexts();
        http_response_code(200);
        header("Content-Type: application/json");
        echo json_encode($result);
    } 

    public function addContext() {
        $data = json_decode(file_get_contents("php://input"));
        $id = $data->id;
        $name = $data->name;
        $context_type = $data->context_type;
        $repository = $data->repository;
        $result = $this->ContextModel->addContext($id, $name, $context_type, $repository);
        http_response_code(200);
        header("Content-Type: application/json");
        echo json_encode($result);
    }

    public function assignCriterion($id) {
        $data = json_decode(file_get_contents("php://input"));
        $criterion = $data->criterion;
        $result = $this->ContextModel->assignCriterion($id, $criterion);
        http_response_code(200);
        header("Content-Type: application/json");
        echo json_encode($result);
    }

    public function assignCriterionValue($id, $criterion) {
        $data = json_decode(file_get_contents("php://input"));
        $value = $data->value;
        $result = $this->ContextModel->assignCriterionValue($id, $criterion, $value);
        http_response_code(200);
        header("Content-Type: application/json");
        echo json_encode($result);
    }
}

?>