<?php

    require $_SERVER['DOCUMENT_ROOT'] . '/SOPCOM/models/Model.php';
    require $_SERVER['DOCUMENT_ROOT'] . '/SOPCOM/views/MethodElementView.php';

    class MethodElement extends Model{
        private $getMethodElement = "SELECT me.id as id, me.name as name, me.abstract as abstract, me.description as description, me.figure as figure FROM method_element me WHERE me.id = ?;";

        private $getMethodElementToMeRel = "SELECT msr.fromME, msr.rel, srt.name FROM me_struct_rel msr, struct_rel_type srt WHERE msr.toME = ? AND msr.rel = srt.id;"; 
        private $getMethodElementToActRel = "SELECT actr.fromME, actr.rel, art.name FROM me_struct_rel actr, activity_rel_type art WHERE actr.toME = ? AND actr.rel = art.id;"; 
        private $getMethodElementToArtRel = "SELECT artr.fromME, artr.rel, art.name FROM me_struct_rel artr, artefact_rel_type art WHERE artr.toME = ? AND artr.rel = art.id;"; 

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

    }
    
?>