<?php
    class Criterion extends Model{
        
        private $getAllCriterion = "SELECT c.id as criterionId, c.name as criterionName, v.id, v.name FROM criterion c, value v WHERE c.id = v.criterion;";

        private $deleteCriterion = "DELETE FROM criterion WHERE id = ?;";

        public function getAllCriterions() {
            $statement = $this->conn->prepare($this->getAllCriterion);
            return $this->executeSelectQuery($statement);
        }

        public function deleteCriterion($id) {
            $statement = $this->conn->prepare($this->deleteCriterion);
            $statement->bind_param('i', $id);
            return $this->executeDeleteQuery($statement);
        }
    }

?>