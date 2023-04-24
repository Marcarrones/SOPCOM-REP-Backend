<?php

    class Strategy extends Model {

        private $getAllStrategies = "SELECT s.id, s.name, s.goal_tgt, goal_src, s.x, s.y FROM strategy s;";

        private $addNewStrategy = "INSERT INTO strategy (id, name, goal_tgt, goal_src, x, y) VALUES (?, ?, ?, ?, ?, ?);";
        


        public function addNewStrategy($id, $name, $goal_tgt, $goal_src, $x, $y) {
            $statement = $this->conn->prepare($this->addNewStrategy);
            $statement->bind_param('ssssss', $id, $name, $goal_tgt, $goal_src, $x, $y);
            return $this->executeInsertQuery($statement);
        }

    
        public function getAllStrategies() {
            $statement = $this->conn->prepare($this->getAllStrategies);
            return $this->executeSelectQuery($statement);
        }
    }

?>