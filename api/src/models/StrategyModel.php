<?php

    class Strategy extends Model {

        private $getAllStrategies = "SELECT s.id, s.name, s.goal_tgt, goal_src, s.x, s.y FROM strategy s;";

        private $addNewStrategy = "INSERT INTO strategy (id, x, y, name, goal_tgt, goal_src) VALUES (?, ?, ?, ?, ?, ?);";

        private $updateStrategy = "UPDATE strategy SET name = ? WHERE id = ?";

        private $updateStrategyPos = "UPDATE strategy SET x = ?, y = ? WHERE id = ?";
        


        public function addNewStrategy($id, $x, $y, $name, $goal_tgt, $goal_src) {
            $statement = $this->conn->prepare($this->addNewStrategy);
            $statement->bind_param('ssssii', $id, $x, $y, $name, $goal_tgt, $goal_src);
            return $this->executeInsertQuery($statement);
        }

    
        public function getAllStrategies() {
            $statement = $this->conn->prepare($this->getAllStrategies);
            return $this->executeSelectQuery($statement);
        }

        public function updateStrategy($id, $name_nou) {
            $statement = $this->conn->prepare($this->updateStrategy);
            $statement->bind_param('ss', $name_nou, $id);
            return $this->executeInsertQuery($statement);
        }

        public function updateStrategyPos($id, $x, $y) {
            $statement = $this->conn->prepare($this->updateStrategyPos);
            $statement->bind_param('sss', $x, $y, $id);
            return $this->executeInsertQuery($statement);
        }



    }

?>