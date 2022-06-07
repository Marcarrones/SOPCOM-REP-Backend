<?php

    class MethodElement extends Model{
        private $getMethodElement = "SELECT me.id as id, me.name as name, me.abstract as abstract, me.description as description, me.figure as figure FROM method_element me WHERE me.id = ?;";

        private $getMethodElementToMeRel = "SELECT msr.fromME as id, msr.rel, srt.name FROM me_struct_rel msr, struct_rel_type srt WHERE msr.toME = ? AND msr.rel = srt.id AND msr.rel <> 1;"; 
        private $getMethodElementToActRel = "SELECT actr.fromME as id, actr.rel, art.name FROM activity_rel actr, activity_rel_type art WHERE actr.toME = ? AND actr.rel = art.id;"; 
        private $getMethodElementToArtRel = "SELECT artr.fromME as id, artr.rel, art.name FROM artefact_rel artr, artefact_rel_type art WHERE artr.toME = ? AND artr.rel = art.id;"; 

        private $getMethodElementFromMeRel = "SELECT msr.toME as id, msr.rel, srt.name FROM me_struct_rel msr, struct_rel_type srt WHERE msr.fromME = ? AND msr.rel = srt.id;"; 
        private $getMethodElementFromActRel = "SELECT actr.toME as id, actr.rel, art.name FROM activity_rel actr, activity_rel_type art WHERE actr.fromME = ? AND actr.rel = art.id;"; 
        private $getMethodElementFromArtRel = "SELECT artr.toME as id, artr.rel, art.name FROM artefact_rel artr, artefact_rel_type art WHERE artr.fromME = ? AND artr.rel = art.id;"; 

        private $getAllMethodElements = "SELECT me.id as id, me.name as name, me.description as description FROM method_element me;";
        private $getAllMethodElementsFilter = "SELECT me.id as id, me.name as name, me.description as description FROM method_element me WHERE type = ?;";

        private $getAllMethodElementTypes = "SELECT met.id, met.name FROM method_element_type met;";

        private $deleteMethodElement = "DELETE FROM method_element WHERE id = ?;";

        private $updateMethodElement = "UPDATE method_element SET name = ?, abstract = ?, description = ?, figure = ? WHERE id = ?;";

        private $getAllMeStructRelTypes = "SELECT id, name FROM struct_rel_type;";
        private $getAllActivityRelTypes = "SELECT id, name FROM activity_rel_type;";
        private $getAllArtefactRelTypes = "SELECT id, name FROM artefact_rel_type;";

        private $addNewMethodElement = "INSERT INTO method_element (id, name, abstract, description, figure, type) VALUES (?, ?, ?, ?, ?, ?);";

        private $addNewMethodElementMeRel = "INSERT INTO me_rel (fromME, toME) VALUES (?, ?);";
        private $addNewMethodElementMeStructRel = "INSERT INTO me_struct_rel (fromME, toME, rel) VALUES (?, ?, ?);";
        private $addNewMethodElementActivityRel = "INSERT INTO activity_rel (fromME, toME, rel) VALUES (?, ?, ?);";
        private $addNewMethodElementArtefactRel = "INSERT INTO artefact_rel (fromME, toME, rel) VALUES (?, ?, ?);";
        private $addNewTool = "INSERT INTO tool (id) VALUES (?)";
        private $addNewArtefact = "INSERT INTO artefact (id) VALUES (?)";
        private $addNewActivity = "INSERT INTO activity (id) VALUES (?)";
        private $addNewRole = "INSERT INTO role (id) VALUES (?)";

        private $checkMethodElementRelation = "SELECT abstract, type FROM method_element WHERE id = ?;";
        private $getRelations = "SELECT * FROM me_rel WHERE fromME = ? AND toME = ?;";
        private $getStructRelationsFromTo = "SELECT fromME, toME FROM me_struct_rel WHERE fromME = ?;";
        private $getMethodChunkFromActivity = "SELECT id FROM method_chunk WHERE activity = ?;";
        private $queryInsertChunkRel = "INSERT INTO chunk_rel(fromMC, toMC, fromME, toME) VALUES (?, ?, ?, ?);";

        private $deleteAllRelationsFrom = "DELETE FROM me_rel WHERE fromME = ?;";

        public function getMethodElementById($id) {
            $statement = $this->conn->prepare($this->getMethodElement);
            $statement->bind_param('s', $id);
            $methodElement = $this->executeSelectQuery($statement);
            return $methodElement;
        }

        public function getMethodElementToRelations($id) {
            $relationsTo = Array();
            $statementMeStrRel = $this->conn->prepare($this->getMethodElementToMeRel);
            $statementMeStrRel->bind_param('s', $id);
            $relationsTo['meStrRel'] = $this->executeSelectQuery($statementMeStrRel);

            $statementActRel = $this->conn->prepare($this->getMethodElementToActRel);
            $statementActRel->bind_param('s', $id);
            $relationsTo['actRel'] = $this->executeSelectQuery($statementActRel);

            $statementActRel = $this->conn->prepare($this->getMethodElementToArtRel);
            $statementActRel->bind_param('s', $id);
            $relationsTo['artRel'] = $this->executeSelectQuery($statementActRel);
            
            return $relationsTo;
        }

        public function getMethodElementFromRelations($id) {
            $relationsTo = Array();
            $statementMeStrRel = $this->conn->prepare($this->getMethodElementFromMeRel);
            $statementMeStrRel->bind_param('s', $id);
            $relationsTo['meStrRel'] = $this->executeSelectQuery($statementMeStrRel);

            $statementActRel = $this->conn->prepare($this->getMethodElementFromActRel);
            $statementActRel->bind_param('s', $id);
            $relationsTo['actRel'] = $this->executeSelectQuery($statementActRel);

            $statementActRel = $this->conn->prepare($this->getMethodElementFromArtRel);
            $statementActRel->bind_param('s', $id);
            $relationsTo['artRel'] = $this->executeSelectQuery($statementActRel);
            
            return $relationsTo;
        }

        public function getAllMethodElements($type) {
            if(isset($type)) {
                $statement = $this->conn->prepare($this->getAllMethodElementsFilter);
                $statement->bind_param('i', $type);
            } else $statement = $this->conn->prepare($this->getAllMethodElements);
            return $this->executeSelectQuery($statement);
        }

        public function getAllMethodElementTypes() {
            $statement = $this->conn->prepare($this->getAllMethodElementTypes);
            return $this->executeSelectQuery($statement);
        }

        public function deleteMethodElementById($id) {
            $statement = $this->conn->prepare($this->deleteMethodElement);
            $statement->bind_param('s', $id);
            return $this->executeDeleteQuery($statement);
        }

        public function getAllMethodElementRelationTypes() {
            $types = Array();
            
            $statementMeRel = $this->conn->prepare($this->getAllMeStructRelTypes);
            $types['me_struct_rel'] = $this->executeSelectQuery($statementMeRel);
            
            $statementActivityRel = $this->conn->prepare($this->getAllActivityRelTypes);
            $types['activity_rel'] = $this->executeSelectQuery($statementActivityRel);

            $statementArtefactRel = $this->conn->prepare($this->getAllArtefactRelTypes);
            $types['artefact_rel'] = $this->executeSelectQuery($statementArtefactRel);

            return $types;
        }

        public function addNewMethodElement($id, $name, $abstract, $description, $figure, $type) {
            $statement = $this->conn->prepare($this->addNewMethodElement);
            $statement->bind_param('ssissi', $id, $name, $abstract, $description, $figure, $type);
            $result = $this->executeInsertQuery($statement);
            if($result == 0) {
                switch($type) {
                    case 1:
                        $statementTool = $this->conn->prepare($this->addNewTool);
                        $statementTool->bind_param('s', $id);
                        $this->executeInsertQuery($statementTool);
                        break;
                    case 2:
                        $statementArt = $this->conn->prepare($this->addNewArtefact);
                        $statementArt->bind_param('s', $id);
                        $this->executeInsertQuery($statementArt);
                        break;
                    case 3;
                        $statementAct = $this->conn->prepare($this->addNewActivity);
                        $statementAct->bind_param('s', $id);
                        $this->executeInsertQuery($statementAct);
                        break;
                    case 4:
                        $statementRole = $this->conn->prepare($this->addNewRole);
                        $statementRole->bind_param('s', $id);
                        $this->executeInsertQuery($statementRole);
                        break;
                }
                return $id;
            }
            else return $result;
        }

        /*
            Function to check restrictions for method element relations
        */
        private function checkElementRelation($idFrom, $idTo, $type, $subtype = 0) {
            $statement = $this->conn->prepare($this->checkMethodElementRelation);
            $statement->bind_param('s', $idFrom);
            $from = $this->executeSelectQuery($statement);
            $statement->bind_param('s', $idTo);
            $to = $this->executeSelectQuery($statement);
            if(count($from) == 0 || count($to) == 0 || $from[0]['type'] != $to[0]['type']) return false; // Elements are the same type
            if($type == 2 && ($from[0]['type'] != 2 || $to[0]['type'] != 2)) return false; // Artefact rel both elements are type activity
            if($type == 3 && ($from[0]['type'] != 3 || $to[0]['type'] != 3)) return false; // Activity rel both elements are type artefact
            if($type == 1 && $subtype == 1 && $to[0]['abstract'] != 1) return false; // Struct rel to element must be abstract when specification
            $statementRel = $this->conn->prepare($this->getRelations);
            $statementRel->bind_param('ss', $idFrom, $idTo);
            $relations = $this->executeSelectQuery($statementRel);
            $statementRel->bind_param('ss', $idTo, $idFrom);
            $relationsRev = $this->executeSelectQuery($statementRel);
            if(count($relations) > 0) return false;
            if($type == 1 && count($relationsRev) > 0) return false;
            return true;
        }

        public function addMethodElementMeStructRel($id, $relations) {
            $statementMeRel = $this->conn->prepare($this->addNewMethodElementMeRel);
            $statementMeStructRel = $this->conn->prepare($this->addNewMethodElementMeStructRel);
            foreach($relations as $relation) {
                if($this->checkElementRelation($id, $relation['id'], 1, $relation['rel'])) {
                    $statementMeRel->bind_param('ss', $id, $relation['id']);
                    $this->executeInsertQuery($statementMeRel);
                    $statementMeStructRel->bind_param('ssi', $id, $relation['id'], $relation['rel']);
                    $this->executeInsertQuery($statementMeStructRel);
                }
            }
        }

        public function addMethodElementActivityRel($id, $relations) {
            $statementMeRel = $this->conn->prepare($this->addNewMethodElementMeRel);
            $statementActivityRel = $this->conn->prepare($this->addNewMethodElementActivityRel);
            foreach($relations as $relation) {
                if($this->checkElementRelation($id, $relation['id'], 1)) {
                    $statementMeRel->bind_param('ss', $id, $relation['id']);
                    $this->executeInsertQuery($statementMeRel);
                    $statementActivityRel->bind_param('ssi', $id, $relation['id'], $relation['rel']);
                    $this->executeInsertQuery($statementActivityRel);
                }
            }
        }

        public function addMethodElementArtefactRel($id, $relations) {
            $statementMeRel = $this->conn->prepare($this->addNewMethodElementMeRel);
            $statementArtefactRel = $this->conn->prepare($this->addNewMethodElementArtefactRel);
            foreach($relations as $relation) {
                if($this->checkElementRelation($id, $relation['id'], 1)) {
                    $statementMeRel->bind_param('ss', $id, $relation['id']);
                    $this->executeInsertQuery($statementMeRel);
                    $statementArtefactRel->bind_param('ssi', $id, $relation['id'], $relation['rel']);
                    $this->executeInsertQuery($statementArtefactRel);
                }
            }
        }

        public function updateMethodElement($id, $name, $abstract, $description, $figure) {
            $statement = $this->conn->prepare($this->updateMethodElement);
            $statement->bind_param('sisss', $name, $abstract, $description, $figure, $id);
            return $this->executeUpdateQuery($statement);
        }

        public function deleteAllRelationsFrom($id) {
            $statement = $this->conn->prepare($this->deleteAllRelationsFrom);
            $statement->bind_param('s', $id);
            return $this->executeDeleteQuery($statement);
        }

        public function updateMethodElementStructRel($id, $relations) {
            $this->addMethodElementMeStructRel($id, $relations);
            $this->updateChunkRel($id, $relations);
        }

        public function updateMethodElementActivityRel($id, $relations) {
            $this->addMethodElementActivityRel($id, $relations);
            $this->updateChunkRel($id);
        }

        public function updateMethodElementArtefactRel($id, $relations) {
            $this->addMethodElementArtefactRel($id, $relations);
        }

        private function updateChunkRel($id) {
            $statement = $this->conn->prepare($this->getStructRelationsFromTo);
            $statement->bind_param('s', $id);
            $relations = $this->executeSelectQuery($statement);
            foreach($relations as $rel) {
                $statementMC = $this->conn->prepare($this->getMethodChunkFromActivity);
                $statementMC->bind_param('s', $rel['fromME']);
                $MCFrom = $this->executeSelectQuery($statementMC);
                $statementMC->bind_param('s', $rel['toME']);
                $MCTo = $this->executeSelectQuery($statementMC);
                if(count($MCFrom) > 0 && count($MCTo)) {
                    $statementInsert = $this->conn->prepare($this->queryInsertChunkRel);
                    $statementInsert->bind_param('ssss', $MCFrom, $MCTo, $rel['fromME'], $rel['toME']);
                    $this->executeInsertQuery($statementInsert);
                }
            }
        }

    }
    
?>