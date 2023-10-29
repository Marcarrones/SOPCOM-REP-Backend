<?php

    class Goal extends Model {

        private $getAllGoals = "SELECT g.id, g.name, g.map, g.x, g.y FROM goal g;";
        
        private $getGoalsWithoutMap = "SELECT g.id, g.name FROM goal g WHERE g.map IS null;";

        private $addNewGoal = "INSERT INTO goal (name) VALUES (?);";

        private $addNewGoalWithMap = "INSERT INTO goal (name, map) VALUES (?, ?);";

        private $addNewGoalWithCoord = "INSERT INTO goal (name, map, x, y) VALUES (?, ?, ?, ?);";

        private $getGoal = "SELECT g.id, g.name, g.x, g.y, g.map FROM goal g WHERE id = ?;";
        
        private $goalStrategies = "SELECT s.id, s.name, s.x, s.y, s.goal_tgt, s.goal_src FROM strategy s WHERE goal_src = ?;";

        private $updateGoal = "UPDATE goal SET name = ?, x = ?, y = ? WHERE id = ?";

        private $updatePos = "UPDATE goal SET x = ?, y = ? WHERE id = ?";

        private $deleteGoalfromMap = "DELETE FROM goal WHERE id = ?;";



        
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


        public function updateGoal($id, $name_nou, $x, $y) {
            $statement = $this->conn->prepare($this->updateGoal);
            $statement->bind_param('sssi', $name_nou, $x, $y, $id);
            return $this->executeInsertQuery($statement);
        }

        public function updatePos($id, $x, $y) {
            $statement = $this->conn->prepare($this->updatePos);
            $statement->bind_param('ssi', $x, $y, $id);
            return $this->executeInsertQuery($statement);
        }

        public function deleteGoalfromMap($id) {
            $statement = $this->conn->prepare($this->deleteGoalfromMap);
            $statement->bind_param('i', $id);
            return $this->executeDeleteQuery($statement);
        }

        public function getGoalsWithoutMap() {
            $statement = $this->conn->prepare($this->getGoalsWithoutMap);
            return $this->executeSelectQuery($statement);
        }


    }

?>