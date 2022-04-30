<?php

    class Goal extends Model {

        private $getAllGoals = "SELECT g.id, g.name FROM goal g;";

        private $addNewGoal = "INSERT INTO goal (name) VALUES (?);";

        public function getAllGoals() {
            $statement = $this->conn->prepare($this->getAllGoals);
            return $this->executeSelectQuery($statement);
        }

        public function addNewGoal($name) {
            $statement = $this->conn->prepare($this->addNewGoal);
            $statement->bind_param('s', $name);
            return $this->executeInsertQuery($statement);
        }
    }

?>