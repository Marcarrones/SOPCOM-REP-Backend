<?php

    class Context extends Model {
        private $getContext = "SELECT c.id, c.name, c.context_type, c.repository FROM context c WHERE c.id = ?;";
        private $getContexts = "SELECT c.id, c.name, c.context_type, c.repository FROM context c;";
        private $getContextTypes = "SELECT c.id, c.name FROM context_type c;";
        private $addContext = "INSERT INTO context (id, name, context_type, repository) VALUES (?, ?, ?, ?);";
        // Context methodchunks
        private $canApply = "SELECT a.context_id, JSON_OBJECT('id', s.id, 'name', s.name , 'x', s.x, 'y', s.y, 'goal_tgt', s.goal_tgt, 'goal_src', s.goal_src, 'methodChunkIds',(SELECT IFNULL(JSON_ARRAYAGG(mc.id), '[]') FROM method_chunk mc WHERE mc.strategy = s.id)) as 'strategy', IFNULL(JSON_ARRAYAGG(
            JSON_OBJECT('id', mc.id, 'name', mc.name, 'description', mc.description, 'activity', mc.activity,
                'goal', JSON_OBJECT('id', g.id, 'name', g.name, 'x', g.x, 'y', g.y, 'map', g.`map`),
                'strategy', JSON_OBJECT('id', s.id, 'name', s.name , 'x', s.x, 'y', s.y, 'goal_tgt', s.goal_tgt, 'goal_src', s.goal_src, 'methodChunkIds',(SELECT IFNULL(JSON_ARRAYAGG(mc.id), '[]') FROM method_chunk mc WHERE mc.strategy = s.id)),
                'repository_id', a.repository_id, 'canApply', a.application ) ), '[]') as 'methodChunks'
            FROM apply a 
                INNER JOIN strategy s  on s.id = a.strategy_id 
                LEFT JOIN method_chunk mc on a.method_chunk_id = mc.id
                LEFT JOIN goal g on g.id = mc.intention
            WHERE context_id = ?
            GROUP BY s.id, a.context_id;";

        private $getSelectedMethodChunks = "SELECT mc.id, mc.name, mc.description, mc.activity, mc.repository as 'repository_id', a.application as 'canApply',
                JSON_OBJECT('id', g.id, 'name', g.name, 'x', g.x, 'y', g.y, 'map', g.`map`) as 'goal',
                JSON_OBJECT('id', s.id, 'name', s.name , 'x', s.x, 'y', s.y, 'goal_tgt', s.goal_tgt, 'goal_src', s.goal_src, 'methodChunkIds', (SELECT IFNULL(JSON_ARRAYAGG(mc.id), '[]') FROM method_chunk mc WHERE mc.strategy = s.id)) as 'strategy'
            FROM selected_method_chunks smc INNER JOIN method_chunk mc on mc.id = smc.idMC INNER JOIN strategy s on s.id = mc.strategy INNER JOIN goal g on g.id = mc.intention INNER JOIN apply a on a.method_chunk_id = smc.idMC AND a.context_id = smc.idContext WHERE smc.idContext = ?";
        
        private $insertSelectedMethodChunk = "INSERT INTO selected_method_chunks (idContext, idMC) VALUES (?, ?)";
        
        // Context Criterion
        private $getCriterionFromContext = "SELECT c.id, c.name, IFNULL( JSON_ARRAYAGG(	JSON_OBJECT( 'id', v.id, 'name', v.name, 'criteriaId', v.criterion, 'assignedMC', (SELECT IFNULL(JSON_ARRAYAGG(amcv.idMc),'[]') FROM assign_method_chunk_value amcv WHERE amcv.value = v.id)) ), '[]') as 'values'
                FROM criterion c 
                    LEFT JOIN value v ON v.criterion = c.id 
                WHERE EXISTS (SELECT * FROM context ct WHERE ct.repository = c.repository AND ct.Id =?) 
            GROUP BY c.id";
        private $getAssignedCriterion = "SELECT c.id, c.name, IFNULL( JSON_ARRAYAGG( JSON_OBJECT( 'id', v.id, 'name', v.name, 'criteriaId', v.criterion, 'assignedMC', (SELECT IFNULL(JSON_ARRAYAGG(amcv.idMc),'[]') FROM assign_method_chunk_value amcv WHERE amcv.value = v.id)) ), '[]') as 'values'
                FROM criterion c
                    INNER JOIN assign_criterion ac ON ac.criterion = c.id
                    INNER JOIN assign_criterion_value acv ON  acv.criterion  = ac.criterion and acv.idContext = ac.idContext 
                    LEFT JOIN value v on v.id = acv.value 
                WHERE ac.idContext = ?
            GROUP BY c.id";

        private $assignCriterion = "INSERT INTO assign_criterion (idContext, criterion) VALUES (?, ?)";
        private $assignCriterionValue = "INSERT INTO assign_criterion_value (idContext, criterion, value) VALUES (?, ?, ?)";
        private $removeCriterionValue = "DELETE FROM assign_criterion_value WHERE idContext = ? AND criterion = ? AND value = ?";
        
        private $deleteSelectedMethodChunk = "DELETE FROM selected_method_chunks WHERE idContext = ? AND idMC = ?";
        
        private $updateContext = "UPDATE context SET name = ?, context_type = ? WHERE id = ?";
        private $deleteContext = "DELETE FROM context WHERE id = ?";
        
        public function getContext($id) {
            $statement = $this->conn->prepare($this->getContext);
            $statement->bind_param('s', $id);
            return $this->executeSelectQuery($statement);
        }

        public function getContextTypes() {
            $statement = $this->conn->prepare($this->getContextTypes);
            return $this->executeSelectQuery($statement);
        }

        public function getContexts() {
            $statement = $this->conn->prepare($this->getContexts);
            return $this->executeSelectQuery($statement);
        }

        public function addContext($id, $name, $context_type, $repository) {
            $statement =$this->conn->prepare($this->addContext);
            $statement->bind_param('ssis', $id, $name, $context_type, $repository);
            return $this->executeInsertQuery($statement);
        }
        // METHOD CHUNKS
        public function getCanApply($id) {
            $statement = $this->conn->prepare($this->canApply);
            $statement->bind_param('s', $id);
            return $this->executeSelectQuery($statement);
        }
        
        public function getSelectedMethodChunks($id) {
            $statement = $this->conn->prepare($this->getSelectedMethodChunks);
            $statement->bind_param('s', $id);
            return $this->executeSelectQuery($statement);
        }

        public function insertSelectedMethodChunk($idContext, $idMC) {
            $statement = $this->conn->prepare($this->insertSelectedMethodChunk);
            $statement->bind_param('ss', $idContext, $idMC);
            return $this->executeInsertQuery($statement);
        }

        // Gets criterion that should be part of the context (by the repository)
        public function getContextCriterion($id) {
            $statement = $this->conn->prepare($this->getCriterionFromContext);
            $statement->bind_param('s', $id);
            return $this->executeSelectQuery($statement);
        }
        // Gets criterion that are assigned to the context
        public function getAssignedCriterion($id) {
            $statement = $this->conn->prepare($this->getAssignedCriterion);
            $statement->bind_param('s', $id);
            return $this->executeSelectQuery($statement);
        }

        public function assignCriterion($idContext, $criterion) {
            $statement = $this->conn->prepare($this->assignCriterion);
            $statement->bind_param('si', $idContext, $criterion);
            return $this->executeInsertQuery($statement);
        }

        public function assignCriterionValue($idContext, $criterion, $value) {
            $statement = $this->conn->prepare($this->assignCriterionValue);
            $statement->bind_param('sii', $idContext, $criterion, $value);
            return $this->executeInsertQuery($statement);
        }

        public function removeCriterionValue($idContext,$criterion,$value){
            $statement = $this->conn->prepare($this->removeCriterionValue);
            $statement->bind_param('sii', $idContext, $criterion, $value);
            return $this->executeDeleteQuery($statement);
        }

        public function deleteSelectedMethodChunk($contextId, $mcId) {
            $statement = $this->conn->prepare($this->deleteSelectedMethodChunk);
            $statement->bind_param('ss', $contextId, $mcId);
            return $this->executeDeleteQuery($statement);
        }

        public function updateContext($id, $name, $context_type) {
            $statement = $this->conn->prepare($this->updateContext);
            $statement->bind_param('sis', $name, $context_type, $id);
            return $this->executeUpdateQuery($statement);
        }

        public function deleteContext($id) {
            $statement = $this->conn->prepare($this->deleteContext);
            $statement->bind_param('s', $id);
            return $this->executeDeleteQuery($statement);
        }
    }
?>