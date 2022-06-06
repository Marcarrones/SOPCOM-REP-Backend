<?php

    class Criterion extends Model{
        
        private $getAllCriterion = "SELECT c.id as criterionId, c.name as criterionName, v.id, v.name FROM criterion c, value v WHERE c.id = v.criterion;";

        private $deleteCriterion = "DELETE FROM criterion WHERE id = ?;";

        private $addNewCriterion = "INSERT INTO criterion (name) VALUES (?);";
        private $addNewValueToCriterion = "INSERT INTO value (name, criterion) VALUES (?, ?);";

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

        public function addNewValueToCriterion($id, $name) {
            $statement = $this->conn->prepare($this->addNewValueToCriterion);
            $statement->bind_param('si', $name, $id);
            return $this->executeInsertQuery($statement);
        }
    }

?>