<?php

    class Map extends Model {

        private $getAllMaps = "SELECT m.id, m.name FROM map m WHERE m.repository = ?;";   

        private $deleteMap = "DELETE FROM map WHERE id = ?;";

        private $getMap = "SELECT m.id, m.name FROM map m WHERE id = ?;";

        private $getMapWithGoals = "SELECT g.id, g.name, g.x, g.y, g.map FROM goal g WHERE g.map = ?;";

        private $getMapStrategies = "SELECT s.id, s.x, s.y, s.name, s.goal_tgt, s.goal_src FROM strategy s, goal g WHERE s.goal_tgt = g.id AND g.map = ?;";

        private $addNewMap = "INSERT INTO map (id, name, repository) VALUES (?, ?, ?);";

        private $updateMap = "UPDATE map SET name = ?, repository = ?, WHERE id = ?";


        
        public function getAllMaps($repository) {
            $statement = $this->conn->prepare($this->getAllMaps);
            $statement->bind_param('s', $repository);
            return $this->executeSelectQuery($statement);
        }


        public function getMap($id) {
            $statement = $this->conn->prepare($this->getMap);
            $statement->bind_param('s', $id);
            return $this->executeSelectQuery($statement);
        }

        public function getMapWithGoals($id) {
            $statement = $this->conn->prepare($this->getMapWithGoals);
            $statement->bind_param('s', $id);
            return $this->executeSelectQuery($statement);
        }

        public function getMapStrategies($id) {
            $statement = $this->conn->prepare($this->getMapStrategies);
            $statement->bind_param('s', $id);
            return $this->executeSelectQuery($statement);
        }

        public function deleteMap($id) {
            $statement = $this->conn->prepare($this->deleteMap);
            $statement->bind_param('s', $id);
            return $this->executeDeleteQuery($statement);
        }

        public function addNewMap($id, $name, $repository) {
            $statement = $this->conn->prepare($this->addNewMap);
            $statement->bind_param('sss', $id, $name, $repository);
            return $this->executeInsertQuery($statement);
        }

        public function updateMap($name, $id, $repository) {
            $statement = $this->conn->prepare($this->updateMap);
            $statement->bind_param('ssi', $name, $repository, $id );
            return $this->executeInsertQuery($statement);
        }
    }

?>