<?php

    class Goal extends Model {

        private $getAllGoals = "SELECT g.id, g.name, g.map, g.x, g.y FROM goal g;";

        private $addNewGoal = "INSERT INTO goal (name) VALUES (?);";

        private $addNewGoalWithMap = "INSERT INTO goal (name, map) VALUES (?, ?);";

        private $addNewGoalWithCoord = "INSERT INTO goal (name, map, x, y) VALUES (?, ?, ?, ?);";

        private $getGoal = "SELECT g.id, g.name, g.x, g.y, g.map FROM goal g WHERE id = ?;";
        
        private $goalStrategies = "SELECT s.id, s.name, s.x, s.y, s.goal_tgt, s.goal_src FROM strategy s WHERE goal_src = ?;";

        private $updateGoal = "UPDATE goal SET name = ? WHERE name = ?";


        
        public function getAllGoals() {
            $statement = $this->conn->prepare($this->getAllGoals);
            return $this->executeSelectQuery($statement);
        }

        public function addNewGoal($name) {
            $statement = $this->conn->prepare($this->addNewGoal);
            $statement->bind_param('s', $name);
            return $this->executeInsertQuery($statement);
        }

        
        
        public function getGoal($id) {
            $statement = $this->conn->prepare($this->getGoal);
            $statement->bind_param('s', $id);
            return $this->executeSelectQuery($statement);
        }

        public function addNewGoalWithMap($name, $map) {
            $statement = $this->conn->prepare($this->addNewGoalWithMap);
            $statement->bind_param('si', $name, $map);
            return $this->executeInsertQuery($statement);
        }


        public function addNewGoalWithCoord($name, $map, $x, $y) {
            $statement = $this->conn->prepare($this->addNewGoalWithCoord);
            $statement->bind_param('siss', $name, $map, $x, $y);
            return $this->executeInsertQuery($statement);
        }


        public function goalStrategies($id) {
            $statement = $this->conn->prepare($this->goalStrategies);
            $statement->bind_param('s', $id);
            return $this->executeSelectQuery($statement);
        }


        public function updateGoal($name_actual, $name_nou) {
            $statement = $this->conn->prepare($this->updateGoal);
            $statement->bind_param('si', $name_nou, $name_actual);
            return $this->executeInsertQuery($statement);
        }




    }

?>