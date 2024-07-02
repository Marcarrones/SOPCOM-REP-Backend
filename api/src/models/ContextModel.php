<?php

    class Context extends Model {
        private $getContext = "SELECT c.id, c.name, c.context_type, c.repository FROM context c WHERE c.id = ?;";
        private $getContexts = "SELECT c.id, c.name, c.context_type, c.repository FROM context c;";
        private $addContext = "INSERT INTO context (id, name, context_type, repository) VALUES (?, ?, ?, ?);";
        private $assignCriterion = "INSERT INTO assign_criterion (idContext, criterion)";
        private $assignCriterionValue = "INSERT INTO assign_criterion_value (idContext, criterion, value)";
        private $updateContext = "UPDATE context SET name = ?, description = ?, status = ? WHERE id = ?";
        
        
        public function getContext($id) {
            $statement = $this->conn->prepare($this->getContext);
            $statement->bind_param('s', $id);
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
    }
?>