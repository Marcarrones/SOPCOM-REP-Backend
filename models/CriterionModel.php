<?php
    class Criterion extends Model{
        
        private $getAllCriterion = "SELECT c.id, c.name, v.id, v.name FROM criterion c, value v WHERE c.id = v.criterion;";

        public function getAllCriterions() {
            $statement = $this->conn->prepare($this->getAllCriterion);
            return $this->executeSelectQuery($statement);
        }
    }

?>