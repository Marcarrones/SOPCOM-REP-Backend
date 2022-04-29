<?php
    class Criterion extends Model{
        
        private $getAllCriterion = "SELECT c.id as criterionId, c.name as criterionName, v.id, v.name FROM criterion c, value v WHERE c.id = v.criterion;";

        private $deleteCriterion = "DELETE FROM criterion WHERE id = ?;";

        public function getAllCriterions() {
            $statement = $this->conn->prepare($this->getAllCriterion);
            return $this->executeSelectQuery($statement);
        }

        public function deleteCriterion() {
            $statement = $this->conn->prepare($this->deleteCriterion);
            return $this->executeDeleteQuery($statement);
        }
    }

?>