<?php

    class Goal extends Model {

        private $getAllGoals = "SELECT g.id, g.name FROM goal g;";

        public function getAllGoals() {
            $statement = $this->conn->prepare($this->getAllGoals);
            return $this->executeSelectQuery($statement);
        }
    }

?>