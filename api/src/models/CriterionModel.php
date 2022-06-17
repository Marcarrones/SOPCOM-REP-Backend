<?php

    class Criterion extends Model{
        
        private $getAllCriterion = "SELECT c.id as criterionId, c.name as criterionName, v.id, v.name FROM criterion c, value v WHERE c.id = v.criterion;";

        private $deleteCriterion = "DELETE FROM criterion WHERE id = ?;";

        private $addNewCriterion = "INSERT INTO criterion (name) VALUES (?);";

        private $updateCriterion = "UPDATE criterion SET name = ? WHERE id = ?";

        private $addNewValueToCriterion = "INSERT INTO value (name, criterion) VALUES (?, ?);";
        private $updateCriterionValue = "UPDATE value SET name = ? WHERE id = ?;";
        private $deleteCriterionValue = "DELETE FROM value WHERE id = ?;";

        private $getCriterion = "SELECT c.id, c.name FROM criterion c WHERE id = ?;";
        private $getCriterionValues = "SELECT v.id, v.name FROM value v WHERE v.criterion = ?;";

        public function getAllCriterions() {
            $statement = $this->conn->prepare($this->getAllCriterion);
            return $this->executeSelectQuery($statement);
        }

        public function getCriterion($id) {
            $statement = $this->conn->prepare($this->getCriterion);
            $statement->bind_param('s', $id);
            return $this->executeSelectQuery($statement);
        }

        public function getCriterionValues($id) {
            $statement = $this->conn->prepare($this->getCriterionValues);
            $statement->bind_param('s', $id);
            return $this->executeSelectQuery($statement);
        }

        public function deleteCriterion($id) {
            $statement = $this->conn->prepare($this->deleteCriterion);
            $statement->bind_param('i', $id);
            return $this->executeDeleteQuery($statement);
        }

        public function addNewCriterion($name) {
            $statement = $this->conn->prepare($this->addNewCriterion);
            $statement->bind_param('s', $name);
            return $this->executeInsertQuery($statement);
        }

        public function updateCriterion($id, $name) {
            $statement = $this->conn->prepare($this->updateCriterion);
            $statement->bind_param('si', $name, $id);
            return $this->executeInsertQuery($statement);
        }

        public function addNewValueToCriterion($id, $name) {
            $statement = $this->conn->prepare($this->addNewValueToCriterion);
            $statement->bind_param('si', $name, $id);
            return $this->executeInsertQuery($statement);
        }

        public function updateCriterionValue($id, $name) {
            $statement = $this->conn->prepare($this->updateCriterionValue);
            $statement->bind_param('si', $name, $id);
            return $this->executeInsertQuery($statement);
        }

        public function deleteCriterionValue($id) {
            $statementExists = $this->conn->prepare("SELECT * FROM assign_method_chunk_value WHERE value = ?");
            $statementExists->bind_param('i', $id);
            $num = $this->executeSelectQuery($statementExists);
            if(count($num) > 0) {
                return "Can not delete value. Remove all method chunks assigned to this value.";
            } else {
                $statement = $this->conn->prepare($this->deleteCriterionValue);
                $statement->bind_param('i', $id);
                return $this->executeInsertQuery($statement);
            }
        }
    }

?>