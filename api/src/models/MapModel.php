<?php

    class Map extends Model {

        private $getAllMaps = "SELECT m.id, m.name FROM map m;";

        private $addNewMap = "INSERT INTO map (id, name) VALUES (?, ?);";

        
        public function getAllMaps() {
            $statement = $this->conn->prepare($this->getAllMaps);
            return $this->executeSelectQuery($statement);
        }


        public function addNewMap($id, $name) {
            $statement = $this->conn->prepare($this->addNewMap);
            $statement->bind_param('ss', $id, $name);
            return $this->executeInsertQuery($statement);
        }
    }

?>