<?php

    class Strategy extends Model {

        private $getAllStrategies = "SELECT s.id, s.name, s.goal_tgt, goal_src, s.x, s.y FROM strategy s;";
        private $getAllStrategieswithMaps = "SELECT s.id AS 'st_id', s.name AS 'st_name', m.id, m.name, s.goal_tgt, g.name AS 'g_name' FROM strategy s, map m, goal g WHERE s.goal_tgt = g.id AND g.map = m.id;";


        private $addNewStrategy = "INSERT INTO strategy (id, x, y, name, goal_tgt, goal_src) VALUES (?, ?, ?, ?, ?, ?);";

        private $updateStrategy = "UPDATE strategy SET name = ? WHERE id = ?";

        private $updateStrategyPos = "UPDATE strategy SET x = ?, y = ? WHERE id = ?";
        
        private $deleteStrategyfromMap = "DELETE FROM strategy WHERE id = ?;";



        public function addNewStrategy($id, $x, $y, $name, $goal_tgt, $goal_src) {
            $statement = $this->conn->prepare($this->addNewStrategy);
            $statement->bind_param('ssssii', $id, $x, $y, $name, $goal_tgt, $goal_src);
            return $this->executeInsertQuery($statement);
        }

    
        public function getAllStrategies() {
            $statement = $this->conn->prepare($this->getAllStrategies);
            return $this->executeSelectQuery($statement);
        }

        public function getAllStrategieswithMaps() {
            $statement = $this->conn->prepare($this->getAllStrategieswithMaps);
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

        public function deleteStrategyfromMap($id) {
            $statement = $this->conn->prepare($this->deleteStrategyfromMap);
            $statement->bind_param('s', $id);
            return $this->executeDeleteQuery($statement);
        }



    }

?>