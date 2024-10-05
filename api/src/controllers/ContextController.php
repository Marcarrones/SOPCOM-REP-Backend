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

    public function getContextTypes() {
        $result = $this->ContextModel->getContextTypes();
        http_response_code(200);
        header("Content-Type: application/json");
        echo json_encode($result);
    }

    public function addContext() {
        $data = json_decode(file_get_contents("php://input"));
        $id = $data->id;
        $name = $data->name;
        $context_type = $data->type;
        $repository = $data->repository;
        $result = $this->ContextModel->addContext($id, $name, $context_type, $repository);
        
        $contextCriterion = $this->ContextModel->getContextCriterion($id);
        foreach($contextCriterion as $criterion) {
            $this->ContextModel->assignCriterion($id, $criterion["id"]);
        }
        http_response_code(200);
        header("Content-Type: application/json");
        echo json_encode($result);
    }

    
    public function getCanApply($id) {
        $result = $this->ContextModel->getCanApply($id);
        http_response_code(200);
        header("Content-Type: application/json");
        echo json_encode($result);
    }

    public function getSelectedMethodChunks($id) {
        $result = $this->ContextModel->getSelectedMethodChunks($id);
        http_response_code(200);
        header("Content-Type: application/json");
        echo json_encode($result);
    }

    public function insertSelectedMethodChunk($id) {
        $data = json_decode(file_get_contents("php://input"));
        $idMC = $data->id;
        $result = $this->ContextModel->insertSelectedMethodChunk($id, $idMC);
        http_response_code(200);
        header("Content-Type: application/json");
        echo json_encode($result);
    }

    public function getContextCriterion($id) {
        $result = $this->ContextModel->getContextCriterion($id);
        http_response_code(200);
        header("Content-Type: application/json");
        echo json_encode($result);
    }
    
    public function getAssignedCriterion($id) {
        $result = $this->ContextModel->getAssignedCriterion($id);
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

    public function removeCriterionValue($id, $criterion, $value) {
        $result = $this->ContextModel->removeCriterionValue($id, $criterion, $value);
        http_response_code(200);
        header("Content-Type: application/json");
        echo json_encode($result);
    }

    public function deleteSelectedMethodChunk($contextId, $mcId) {
        $result = $this->ContextModel->deleteSelectedMethodChunk($contextId, $mcId);
        http_response_code(200);
        header("Content-Type: application/json");
        echo json_encode($result);
    }

    public function updateContext($id) {
        $body = json_decode(file_get_contents('php://input'), true);
        $result = $this->ContextModel->updateContext($id, $body["name"], $body["type"]);
        http_response_code(200);
        header("Content-Type: application/json");
        echo json_encode($result);
    }

    public function deleteContext($id) {
        $result = $this->ContextModel->deleteContext($id);
        http_response_code(200);
        header("Content-Type: application/json");
        echo json_encode($result);
    }
}

?>