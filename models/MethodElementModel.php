<?php

    require $_SERVER['DOCUMENT_ROOT'] . '/SOPCOM/models/Model.php';
    require $_SERVER['DOCUMENT_ROOT'] . '/SOPCOM/views/MethodElementView.php';

    class MethodElement extends Model{
        private $getMethodElement = "SELECT me.id as id, me.name as name, me.abstract as abstract, me.description as description, me.figure as figure FROM method_element me WHERE me.id = ?;";

        private $getMethodElementToMeRel = "SELECT msr.fromME, msr.rel, srt.name FROM me_struct_rel msr, struct_rel_type srt WHERE msr.toME = ? AND msr.rel = srt.id;"; 
        private $getMethodElementToActRel = "SELECT actr.fromME, actr.rel, art.name FROM activity_rel actr, activity_rel_type art WHERE actr.toME = ? AND actr.rel = art.id;"; 
        private $getMethodElementToArtRel = "SELECT artr.fromME, artr.rel, art.name FROM artefact_rel artr, artefact_rel_type art WHERE artr.toME = ? AND artr.rel = art.id;"; 

        private $getAllMethodElements = "SELECT me.id as id, me.name as name, me.description as description FROM method_element me;";
        private $getAllMethodElementsFilter = "SELECT me.id as id, me.name as name, me.description as description FROM method_element me WHERE type = ?;";

        private $getAllMethodElementTypes = "SELECT met.id, met.name FROM method_element_type met;";

        private $deleteMethodElement = "DELETE FROM method_element WHERE id = ?;";

        private $getAllMeStructRelTypes = "SELECT id, name FROM struct_rel_type;";
        private $getAllActivityRelTypes = "SELECT id, name FROM activity_rel_type;";
        private $getAllArtefactRelTypes = "SELECT id, name FROM artefact_rel_type;";

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

    }
    
?>