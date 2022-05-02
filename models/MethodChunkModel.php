<?php

    class MethodChunk extends Model{

        private $getMethodChunk = "SELECT mc.id, mc.name, mc.description, g.name as intention FROM method_chunk mc LEFT JOIN goal g ON mc.intention = g.id WHERE mc.id = ?;";
        private $getMethodChunkIntention = "SELECT g.id, g.name FROM goal g, method_chunk mc WHERE mc.id = ? AND mc.intention = g.id;";
        
        public function getMethodChunk($id) {
            $statement = $this->conn->prepare($this->getMethodChunk);
            $statement->bind_param('s', $id);
            return $this->executeSelectQuery($statement);
        }

        public function getMethodChunkIntention($id) {
            $statement = $this->conn->prepare($this->getMethodChunkIntention);
            $statement->bind_param('s', $id);
            return $this->executeSelectQuery($statement);
        }
        

    }

?>