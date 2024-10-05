<?php

    class MethodChunk extends Model{

        private $getMethodChunk = "SELECT mc.id, mc.name, mc.description, mc.intention, mc.strategy, mc.intention FROM method_chunk mc WHERE mc.id = ?;";
        private $getMethodChunkIntention = "SELECT g.name FROM goal g, method_chunk mc WHERE mc.id = ? AND mc.intention = g.id;";
        private $getMethodChunkStrategy = "SELECT mc.strategy FROM method_chunk mc WHERE mc.id = ?;";
        private $getStrategyTarget = "SELECT s.goal_tgt FROM strategy s WHERE s.id = ?;";
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
        private $getMethodChunkContextCriteria = "SELECT c.id as criterionId, c.name as criterionName, v.id as id, v.name as name 
                                                FROM assign_method_chunk_value amcv 
                                                RIGHT JOIN method_chunk mc ON amcv.idMC = mc.id 
                                                LEFT JOIN criterion c ON amcv.criterion = c.id
                                                LEFT JOIN value v ON amcv.value = v.id
                                                WHERE c.name IS NOT NULL and v.name IS NOT NULL AND mc.id = ?;";
        private $getMethodChunkFromRelations = "SELECT cr.fromME, cr.toME, cr.fromMC, cr.toMC FROM chunk_rel cr WHERE cr.fromMC = ?;";
        private $getMethodChunkToRelations = "SELECT cr.fromME, cr.toME, cr.fromMC, cr.toMC FROM chunk_rel cr WHERE cr.toMC = ?;";
        private $getMeStructRel = "SELECT msr.fromME, msr.toME, msr.rel, srt.name FROM me_struct_rel msr, struct_rel_type srt WHERE msr.fromME = ? AND msr.toME = ? AND msr.rel = srt.id;";
        private $getActRel = "SELECT ar.fromME, ar.toME, ar.rel, art.name FROM activity_rel ar, activity_rel_type art WHERE ar.fromME = ? AND ar.toME = ? AND ar.rel = art.id;";

        private $deleteMethodChunk = "DELETE FROM method_chunk WHERE id = ?;";

        private $updateMethodChunk = "UPDATE method_chunk SET name = ?, description = ?, activity = ?, strategy = ?, repository = ? WHERE id = ?;";

        private $addNewMethodChunk = "INSERT INTO method_chunk (id, name, description, activity, repository) VALUES (?, ?, ?, ?, ?);";
        private $addNewMethodChunkStrategy = "UPDATE method_chunk SET strategy = ? WHERE id = ?;";
        private $assignNewIntention = "UPDATE method_chunk SET intention = ? WHERE id = ?;";
        private $addNewMethodChunkTool = "INSERT INTO method_chunk_uses_tool (idMC, idME) VALUES (?, ?);";
        private $addNewMethodChunkConsumedArtefact = "INSERT INTO method_chunk_consumes_artefact (idMC, idME) VALUES (?, ?);";
        private $addNewMethodChunkProducedArtefact = "INSERT INTO method_chunk_produces_artefact (idMC, idME) VALUES (?, ?);";
        private $addNewMethodChunkRole = "INSERT INTO method_chunk_includes_role (idMC, idME, isSet) VALUES (?, ?, ?);";
        private $addAssignMethodChunk = "INSERT INTO assign_method_chunk (idMC, criterion) VALUES (?, ?);";
        private $addAssignMethodChunkValue = "INSERT INTO assign_method_chunk_value (idMC, criterion, value) VALUES (?, ?, ?);";
        private $addChunkRel = "INSERT INTO chunk_rel (fromMC, toMC, fromME, toME) VALUES (?, ?, ?, ?);";
        private $getProcessPartRelations = "SELECT mr.fromME, mr.toME FROM me_rel mr WHERE mr.fromME = ? OR mr.toME = ?;";
        private $getMethodChunkIdFromActivity = "SELECT mc.id FROM method_chunk mc WHERE mc.activity = ?;";

        private $getAllMethodChunks = "SELECT mc.id, mc.name, mc.description, mc.activity, mc.intention, mc.strategy FROM method_chunk mc WHERE mc.repository = ?;";
        private $getAllMethodChunkwithMap = "SELECT mc.id, mc.name, mc.description, mc.activity, mc.intention, mc.strategy, g.map FROM method_chunk mc, strategy s, goal g WHERE mc.id IS NOT NULL AND mc.strategy = s.id AND s.goal_tgt = g.id AND mc.repository = ?;";

        private $deleteAllMethodChunkTools = "DELETE FROM method_chunk_uses_tool WHERE idMC = ?;";
        private $deleteAllMethodChunkConsumedArtefacts = "DELETE FROM method_chunk_consumes_artefact WHERE idMC = ?;";
        private $deleteAllMethodChunkProducedArtefacts = "DELETE FROM method_chunk_produces_artefact WHERE idMC = ?;";
        private $deleteAllMethodChunkRoles = "DELETE FROM method_chunk_includes_role WHERE idMC = ?;";
        private $deleteAllMethodChunkContextCriteria = "DELETE FROM assign_method_chunk WHERE idMC = ?;";

        public function getMethodChunk($id) {
            $statement = $this->conn->prepare($this->getMethodChunk);
            $statement->bind_param('s', $id);
            return $this->executeSelectQuery($statement);
        }

        public function getMethodChunkIntention($id) {
            $statement = $this->conn->prepare($this->getMethodChunkIntention);
            $statement->bind_param('i', $id);
            return $this->executeSelectQuery($statement);
        }

        public function getMethodChunkStrategy($id) {
            $statement = $this->conn->prepare($this->getMethodChunkStrategy);
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

        public function getStrategyTarget($strategy) {
            $statement = $this->conn->prepare($this->getStrategyTarget);
            $statement->bind_param('s', $strategy);
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
                $relsStruct = $this->executeSelectQuery($statementMeStructRel);
                if(count($relsStruct) > 0) {
                    $relsStruct[0] += ['fromMC'=> $rel['fromMC']];
                    $relsStruct[0] += ['toMC' => $rel['toMC']];
                    $result['from']['me_struct_rel'][] = $relsStruct[0];
                }
                $statementActRel->bind_param('ss', $rel['fromME'], $rel['toME']);
                $relsAct = $this->executeSelectQuery($statementActRel);
                if(count($relsAct) > 0) {
                    $relsAct[0] += ['fromMC'=> $rel['fromMC']];
                    $relsAct[0] += ['toMC' => $rel['toMC']];
                    $result['from']['activity_rel'][] = $relsAct[0];
                }
            }

            $statementRelationsTo = $this->conn->prepare($this->getMethodChunkToRelations);
            $statementRelationsTo->bind_param('s', $id);
            $relationsTo = $this->executeSelectQuery($statementRelationsTo);
            $result['to'] = [];
            foreach($relationsTo as $rel) {
                $statementMeStructRel->bind_param('ss', $rel['fromME'], $rel['toME']);
                $relsStruct = $this->executeSelectQuery($statementMeStructRel);
                $relsStruct = array_filter($relsStruct, function($rel) { return $rel['rel'] != 1;});
                if(count($relsStruct) > 0) {
                    $relsStruct[0] += ['fromMC'=> $rel['fromMC']];
                    $relsStruct[0] += ['toMC' => $rel['toMC']];
                    $result['to']['me_struct_rel'][] =  $relsStruct[0];
                }
                $statementActRel->bind_param('ss', $rel['fromME'], $rel['toME']);
                $relsAct = $this->executeSelectQuery($statementActRel);
                if(count($relsAct) > 0) {
                    $relsAct[0] += ['fromMC'=> $rel['fromMC']];
                    $relsAct[0] += ['toMC' => $rel['toMC']];
                    var_dump($relsAct[0]);
                    $result['to']['activity_rel'][] = $relsAct[0];
                }
            }
            return $result;
        }

        public function getAllMethodChunk($repository) {
            $statement = $this->conn->prepare($this->getAllMethodChunks);
            $statement->bind_param('s', $repository);
            return $this->executeSelectQuery($statement);
        }

        public function getAllMethodChunkwithMap($repository) {
            $statement = $this->conn->prepare($this->getAllMethodChunkwithMap);
            $statement->bind_param('s', $repository);
            return $this->executeSelectQuery($statement);
        }

        public function deleteMethodChunk($id) {
            $statement = $this->conn->prepare($this->deleteMethodChunk);
            $statement->bind_param('s', $id);
            return $this->executeDeleteQuery($statement);
        }

        public function updateMethodChunk($id, $name, $description, $activity, $repository, $strategy) {
            $statement = $this->conn->prepare($this->updateMethodChunk);
            $statement->bind_param('ssssss', $name, $description, $activity, $strategy, $repository, $id);
            return $this->executeUpdateQuery($statement);
        }

        public function addNewMethodChunk($id, $name, $description, $activity, $repository) {
            $statement = $this->conn->prepare($this->addNewMethodChunk);
            $statement->bind_param('sssss', $id, $name, $description, $activity, $repository);
            $result = $this->executeInsertQuery($statement);
            if($result == 0) {
                $statementRelations = $this->conn->prepare($this->getProcessPartRelations);
                $statementRelations->bind_param('ss', $activity, $activity);
                $relations = $this->executeSelectQuery($statementRelations);
                $statementIdRel = $this->conn->prepare($this->getMethodChunkIdFromActivity);
                $statementChunkRel = $this->conn->prepare($this->addChunkRel);
                foreach($relations as $relation) {
                    if($relation['fromME'] == $activity) {
                        $statementIdRel->bind_param('s', $relation['toME']);
                        $idRel = $this->executeSelectQuery($statementIdRel)[0]['id'];
                        $statementChunkRel->bind_param('ssss', $id, $idRel, $activity, $relation['toME']);
                        $this->executeInsertQuery($statementChunkRel);
                    } else if($relation['toME'] == $activity) {
                        $statementIdRel->bind_param('s', $relation['fromME']);
                        $idRel = $this->executeSelectQuery($statementIdRel)[0]['id'];
                        $statementChunkRel->bind_param('ssss', $idRel, $id, $relation['fromME'], $activity);
                        $this->executeInsertQuery($statementChunkRel);
                    }
                }
                $result = $id;
            }
            return $result;
        }

        public function addNewMethodChunkTools($id, $tools) {
            $statement = $this->conn->prepare($this->addNewMethodChunkTool);
            foreach($tools as $tool){
                $statement->bind_param('ss', $id, $tool);
                $this->executeInsertQuery($statement);
            }
            return;
        }

        public function addNewMethodChunkStrategy($strategy, $id) {
            $statement = $this->conn->prepare($this->addNewMethodChunkStrategy);
            $statement->bind_param('ss', $strategy, $id);
            return $this->executeInsertQuery($statement);
        }

        public function assignNewIntention($id, $target) {
            $statement = $this->conn->prepare($this->assignNewIntention);
            $statement->bind_param('is', $target, $id);
            return $this->executeInsertQuery($statement);
        }
        

        public function addNewMethodChunkConsumedArtefacts($id, $artefacts) {
            $statement = $this->conn->prepare($this->addNewMethodChunkConsumedArtefact);
            foreach($artefacts as $artefact){
                $statement->bind_param('ss', $id, $artefact);
                $this->executeInsertQuery($statement);
            }
            return;
        }

        public function addNewMethodChunkProducedArtefacts($id, $artefacts) {
            $statement = $this->conn->prepare($this->addNewMethodChunkProducedArtefact);
            foreach($artefacts as $artefact){
                $statement->bind_param('ss', $id, $artefact);
                $this->executeInsertQuery($statement);
            }
            return;
        }

        public function addNewMethodChunkRoles($id, $roles) {
            $statement = $this->conn->prepare($this->addNewMethodChunkRole);
            foreach($roles as $role){
                $statement->bind_param('ssi', $id, $role['id'], $role['isSet']);
                $this->executeInsertQuery($statement);
            }
            return;
        }

        public function addMethodChunkContextCriteria($id, $context) {
            $statementAMC = $this->conn->prepare($this->addAssignMethodChunk);
            $statementAMCV = $this->conn->prepare($this->addAssignMethodChunkValue);
            foreach($context as $cnt) {
                $statementAMC->bind_param('si', $id, $cnt['criterionId']);
                $this->executeInsertQuery($statementAMC);
                foreach($cnt['value'] as $val) {
                    $statementAMCV->bind_param('sii', $id, $cnt['criterionId'], $val);
                    $this->executeInsertQuery($statementAMCV);
                }
            }
        }

        public function updateMethodChunkTools($id, $tools) {
            $statement = $this->conn->prepare($this->deleteAllMethodChunkTools);
            $statement->bind_param('s', $id);
            $this->executeDeleteQuery($statement);
            $this->addNewMethodChunkTools($id, $tools);
        }

        public function updateMethodChunkConsumedArtefacts($id, $artefacts) {
            $statement = $this->conn->prepare($this->deleteAllMethodChunkConsumedArtefacts);
            $statement->bind_param('s', $id);
            $this->executeDeleteQuery($statement);
            $this->addNewMethodChunkConsumedArtefacts($id, $artefacts);
        }

        public function updateMethodChunkProducedArtefacts($id, $artefacts) {
            $statement = $this->conn->prepare($this->deleteAllMethodChunkProducedArtefacts);
            $statement->bind_param('s', $id);
            $this->executeDeleteQuery($statement);
            $this->addNewMethodChunkProducedArtefacts($id, $artefacts);
        }

        public function updateMethodChunkRoles($id, $roles) {
            $statement = $this->conn->prepare($this->deleteAllMethodChunkRoles);
            $statement->bind_param('s', $id);
            $this->executeDeleteQuery($statement);
            $this->addNewMethodChunkRoles($id, $roles);
        }

        public function updateMethodChunkContextCriteria($id, $context) {
            $statement = $this->conn->prepare($this->deleteAllMethodChunkContextCriteria);
            $statement->bind_param('s', $id);
            $this->executeDeleteQuery($statement);
            $this->addMethodChunkContextCriteria($id, $context);
        }

    }

?>