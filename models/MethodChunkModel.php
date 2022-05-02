<?php

    class MethodChunk extends Model{

        private $getMethodChunk = "SELECT mc.id, mc.name, mc.description, g.name as intention FROM method_chunk mc LEFT JOIN goal g ON mc.intention = g.id WHERE mc.id = ?;";
        private $getMethodChunkIntention = "SELECT g.id, g.name FROM goal g, method_chunk mc WHERE mc.id = ? AND mc.intention = g.id;";
        private $getMethodChunkTools = "SELECT me.id, me.name, me.description, me.figure 
                                            FROM method_element me
                                            RIGHT JOIN tool t ON me.id = t.id 
                                            RIGHT JOIN method_chunk_uses_tool mcut ON t.id = mcut.idME
                                            RIGHT JOIN method_chunk mc ON mcut.idMC = mc.id
                                            WHERE me.id IS NOT NULL AND mc.id = ?;";
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
        
        public function getMethodChunkTools($id) {
            $statement = $this->conn->prepare($this->getMethodChunkTools);
            $statement->bind_param('s', $id);
            return $this->executeSelectQuery($statement);
        }

    }

?>