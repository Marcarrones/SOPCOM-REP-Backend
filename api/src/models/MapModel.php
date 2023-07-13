<?php

    class Map extends Model {

        private $getAllMaps = "SELECT m.id, m.name, m.pruebas FROM map m;";   

        private $deleteMap = "DELETE FROM map WHERE id = ?;";

        private $getMap = "SELECT m.id, m.name, m.pruebas FROM map m WHERE id = ?;";

        private $getMapWithGoals = "SELECT g.id, g.name, g.x, g.y, g.map FROM goal g WHERE g.map = ?;";

        private $addNewMap = "INSERT INTO map (id, name, pruebas) VALUES (?, ?, ?);";

        private $updateMap = "UPDATE map SET pruebas = ? WHERE id = ?";


        
        public function getAllMaps() {
            $statement = $this->conn->prepare($this->getAllMaps);
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


        public function deleteMap($id) {
            $statement = $this->conn->prepare($this->deleteMap);
            $statement->bind_param('s', $id);
            return $this->executeDeleteQuery($statement);
        }

        public function addNewMap($id, $name, $pruebas) {
            $statement = $this->conn->prepare($this->addNewMap);
            $statement->bind_param('sss', $id, $name, $pruebas);
            return $this->executeInsertQuery($statement);
        }

        public function updateMap($pruebas, $id) {
            $statement = $this->conn->prepare($this->updateMap);
            $statement->bind_param('pi', $pruebas, $id);
            return $this->executeInsertQuery($statement);
        }
    }

?>