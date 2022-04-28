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

        private $addNewMethodElement = "INSERT INTO method_element (id, name, abstract, description, figure, type) VALUES (?, ?, ?, ?, ?, ?);";

        private $addNewMethodElementMeRel = "INSERT INTO me_rel (fromME, toME) VALUES (?, ?);";
        private $addNewMethodElementMeStructRel = "INSERT INTO me_struct_rel VALUES (fromME, toME, rel);";
        private $addNewMethodElementActivityRel = "INSERT INTO activity_rel VALUES (fromME, toME, rel);";
        private $addNewMethodElementArtefactRel = "INSERT INTO artefact_rel VALUES (fromME, toME, rel);";

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

        public function addNewMethodElement($id, $name, $abstract, $description, $figure, $type) {
            $statement = $this->conn->prepare($this->addNewMethodElement);
            $statement->bind_param('ssissi', $id, $name, $abstract, $description, $figure, $type);
            return $this->executeInsertQuery($statement);
        }

        public function addMethodElementMeStructRel($id, $relations) {
            $statementMeRel = $this->conn->prepare($this->addNewMethodElementMeRel);
            $statementMeStructRel = $this->conn->prepare($this->addNewMethodElementMeStructRel);
            foreach($relations as $relation) {
                $statementMeRel->bind_param('ss', $id, $relation['id']);
                $this->executeInsertQuery($statementMeRel);
                $statementMeStructRel->bind_param($id, $relation['id'], $relation['rel']);
                $this->executeInsertQuery($statementMeStructRel);
            }
        }

        public function addMethodElementActivityRel($id, $relations) {

        }

        public function addMethodElementArtefactRel($id, $relations) {

        }

    }
    
?>