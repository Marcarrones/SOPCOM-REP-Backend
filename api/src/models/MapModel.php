<?php

    class Map extends Model {

        private $getAllMaps = "SELECT m.id, m.name FROM map m;";   

        private $deleteMap = "DELETE FROM map WHERE id = ?;";

        private $getMap = "SELECT m.id, m.name FROM map m WHERE id = ?;";

        private $addNewMap = "INSERT INTO map (id, name) VALUES (?, ?);";

        
        public function getAllMaps() {
            $statement = $this->conn->prepare($this->getAllMaps);
            return $this->executeSelectQuery($statement);
        }


        public function getMap($id) {
            $statement = $this->conn->prepare($this->getMap);
            $statement->bind_param('s', $id);
            return $this->executeSelectQuery($statement);
        }


        public function deleteMap($id) {
            $statement = $this->conn->prepare($this->deleteMap);
            $statement->bind_param('s', $id);
            return $this->executeDeleteQuery($statement);
        }

        public function addNewMap($id, $name) {
            $statement = $this->conn->prepare($this->addNewMap);
            $statement->bind_param('ss', $id, $name);
            return $this->executeInsertQuery($statement);
        }
    }

?>