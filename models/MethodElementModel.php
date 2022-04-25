<?php

    require $_SERVER['DOCUMENT_ROOT'] . '/controllers/Model.php';

    class MethodElement extends Model{
        private $getMethodElement = "SELECT me.id as id, me.name as name, me.abstract as abstract, me.description as description, me.figure as figure FROM method_element me WHERE me.id = ?;";

        public function getMethodElementById($id) {
            $statement = $this->conn->prepare($this->getMethodElement);
            $statement->bind_param('s', $id);
            $methodElement = $this->executeSelectQuery($statement);
            return $methodElement;
        }

    }
    
?>