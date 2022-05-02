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
        private $getMethodChunkConsumedArtefacts = "SELECT me.id, me.name, me.description, me.figure 
                                            FROM method_element me
                                            RIGHT JOIN artefact a ON me.id = a.id 
                                            RIGHT JOIN method_chunk_consumes_artefact mcca ON a.id = mcca.idME
                                            RIGHT JOIN method_chunk mc ON mcca.idMC = mc.id
                                            WHERE me.id IS NOT NULL AND mc.id = ?;";
        private $getMethodChunkProducedArtefacts = "SELECT me.id, me.name, me.description, me.figure 
                                            FROM method_element me
                                            RIGHT JOIN artefact a ON me.id = a.id 
                                            RIGHT JOIN method_chunk_produces_artefact mcpa ON a.id = mcpa.idME
                                            RIGHT JOIN method_chunk mc ON mcpa.idMC = mc.id
                                            WHERE me.id IS NOT NULL AND mc.id = ?;";
        private $getMethodChunkActivity = "SELECT me.id, me.name, me.abstract, me.description, me.figure 
                                            FROM method_element me
                                            RIGHT JOIN method_chunk mc ON mc.activity = me.id
                                            WHERE me.id IS NOT NULL AND mc.id = ?;";
        private $getMethodChunkRoles = "SELECT me.id, me.name, me.description, me.figure, mcir.isSet
                                                FROM method_element me
                                                RIGHT JOIN role r ON me.id = r.id 
                                                RIGHT JOIN method_chunk_includes_role mcir ON r.id = mcir.idME
                                                RIGHT JOIN method_chunk mc ON mcir.idMC = mc.id
                                                WHERE me.id IS NOT NULL AND mc.id = ?;";
        private $getMethodChunkContextCriteria = "SELECT c.name as criterion, v.name as value 
                                                FROM assign_method_chunk_value amcv 
                                                RIGHT JOIN method_chunk mc ON amcv.idMC = mc.id 
                                                LEFT JOIN criterion c ON amcv.criterion = c.id
                                                LEFT JOIN value v ON amcv.value = v.id
                                                WHERE c.name IS NOT NULL and v.name IS NOT NULL AND mc.id = ?;";
        private $getMethodChunkFromRelations = "SELECT cr.fromME, cr.toME FROM chunk_rel cr WHERE cr.fromMC = ?;";
        private $getMethodChunkToRelations = "SELECT cr.fromME, cr.toME FROM chunk_rel cr WHERE cr.toMC = ?;";
        private $getMeStructRel = "SELECT * FROM me_struct_rel msr WHERE msr.fromME = ? AND msr.toME = ?;";
        private $getActRel = "SELECT * FROM activity_rel ar WHERE ar.fromME = ? AND ar.toME = ?;";

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

        public function getMethodChunkArtefacts($id) {
            $response = Array();
            $statementConsumed = $this->conn->prepare($this->getMethodChunkConsumedArtefacts);
            $statementConsumed->bind_param('s', $id);
            $response['consumed'] = $this->executeSelectQuery($statementConsumed);
            $statementProduced = $this->conn->prepare($this->getMethodChunkProducedArtefacts);
            $statementProduced->bind_param('s', $id);
            $response['produced'] = $this->executeSelectQuery($statementProduced);
            return $response;
        }

        public function getMethodChunkActivity($id) {
            $statement = $this->conn->prepare($this->getMethodChunkActivity);
            $statement->bind_param('s', $id);
            return $this->executeSelectQuery($statement);
        }

        public function getMethodChunkRoles($id) {
            $statement = $this->conn->prepare($this->getMethodChunkRoles);
            $statement->bind_param('s', $id);
            return $this->executeSelectQuery($statement);
        }

        public function getMethodChunkContextCriteria($id) {
            $statement = $this->conn->prepare($this->getMethodChunkContextCriteria);
            $statement->bind_param('s', $id);
            return $this->executeSelectQuery($statement);
        }

        public function getMethodChunkRelations($id) {
            $result = Array('from' => Array('me_struct_rel' => [], 'activity_rel' => []), 'to' => Array('me_struct_rel' => [], 'activity_rel' => []));
            $statementRelationsFrom = $this->conn->prepare($this->getMethodChunkFromRelations);
            $statementRelationsFrom->bind_param('s', $id);
            $relationsFrom = $this->executeSelectQuery($statementRelationsFrom);
            $statementMeStructRel = $this->conn->prepare($this->getMeStructRel);
            $statementActRel = $this->conn->prepare($this->getActRel);
            foreach($relationsFrom as $rel) {
                $statementMeStructRel->bind_param('ss', $rel['fromME'], $rel['toME']);
                $result['from']['me_struct_rel'][] = $this->executeSelectQuery($statementMeStructRel);
                $statementActRel->bind_param('ss', $rel['fromME'], $rel['toME']);
                $result['from']['activity_rel'][] = $this->executeSelectQuery($statementActRel);
            }

            $statementRelationsTo = $this->conn->prepare($this->getMethodChunkToRelations);
            $statementRelationsTo->bind_param('s', $id);
            $relationsTo = $this->executeSelectQuery($statementRelationsTo);
            $result['to'] = [];
            foreach($relationsTo as $rel) {
                $statementMeStructRel->bind_param('ss', $rel['fromME'], $rel['toME']);
                $result['to']['me_struct_rel'][] = $this->executeSelectQuery($statementMeStructRel);
                $statementActRel->bind_param('ss', $rel['fromME'], $rel['toME']);
                $result['to']['activity_rel'][] = $this->executeSelectQuery($statementActRel);
            }
            return $result;
        }

        public function deleteMethodChunk($id) {
            $statement = $this->conn->prepare($this->deleteMethodChunk);
            $statement->bind_param('s', $id);
            return $this->executeDeleteQuery($statement);
        }

    }

?>