<?php

    class Repository extends Model {

        private $getRepositoryStatus = "SELECT s.id, s.name FROM repository_status s;";

        private $getAllRepositories = "SELECT r.id, r.name, r.description, JSON_OBJECT('id', rs.id, 'name', rs.name) as 'status' FROM repository r JOIN repository_status rs ON r.status = rs.id;" ;
        //"SELECT r.id, r.name, r.description, r.status FROM repository r;";   

        private $getRepository = "SELECT r.id, r.name, r.description, JSON_OBJECT('id', rs.id, 'name', rs.name) as 'status' FROM repository r JOIN repository_status rs ON r.status = rs.id WHERE r.id = ?;";
        
        private $deleteRepository = "DELETE FROM repository WHERE id = ?;";

        private $addNewRepository = "INSERT INTO repository (id, name, description, status) VALUES (?, ?, ?, ?);";

        private $updateRepository = "UPDATE repository SET name = ?, description = ?, status = ? WHERE id = ?";

        public function getRepositoryStatus() {
            $statement = $this->conn->prepare($this->getRepositoryStatus);
            return $this->executeSelectQuery($statement);
        }
        
        public function getAllRepositories() {
            $statement = $this->conn->prepare($this->getAllRepositories);
            return $this->executeSelectQuery($statement);
        }

        public function getRepository($id) {
            $statement = $this->conn->prepare($this->getRepository);
            $statement->bind_param('s', $id);
            return $this->executeSelectQuery($statement);
        }

        public function deleteRepository($id) {
            $statement = $this->conn->prepare($this->deleteRepository);
            $statement->bind_param('s', $id);
            return $this->executeDeleteQuery($statement);
        }

        public function addNewRepository($id, $name, $description, $status) {
            $statement = $this->conn->prepare($this->addNewRepository);
            $statement->bind_param('sssi', $id, $name, $description, $status);
            return $this->executeInsertQuery($statement);
        }

        public function updateRepository($id, $name, $description, $status) {
            $statement = $this->conn->prepare($this->updateRepository);
            $statement->bind_param('ssis', $name, $description, $status, $id );
            return $this->executeInsertQuery($statement);
        }
    }

?>