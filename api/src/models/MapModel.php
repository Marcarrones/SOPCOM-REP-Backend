<?php

    class Map extends Model {

        private $getAllMaps = "SELECT m.id, m.name FROM map m WHERE m.repository = ?;";   

        private $deleteMap = "DELETE FROM map WHERE id = ?;";

        private $getMap = "SELECT m.id, m.name FROM map m WHERE id = ?;";
        private $getFullMap = "SELECT m.id, m.name, m.repository, 
            (SELECT JSON_OBJECT('id', g.id,'name', g.name,'x', g.x, 'y', g.y, 'map', g.`map`) FROM goal g WHERE g.id = m.start) as 'start',
            (SELECT JSON_OBJECT('id', g.id,'name', g.name,'x', g.x, 'y', g.y, 'map', g.`map`) FROM goal g WHERE g.id = m.stop) as 'stop',
            (SELECT IFNULL( JSON_ARRAYAGG(JSON_OBJECT('id', s.id, 'name', s.name, 'x', s.x, 'y', s.y, 'goal_tgt', s.goal_tgt, 'goal_src', s.goal_src, 'methodChunkIds',(SELECT IFNULL(JSON_ARRAYAGG(mc.id), '[]') FROM method_chunk mc WHERE mc.strategy = s.id))), '[]') FROM strategy s WHERE EXISTS (SELECT * FROM goal g WHERE (s.goal_tgt = g.id or s.goal_src  = g.id) and g.map = m.id ) ) as 'strategies',
            (SELECT IFNULL( JSON_ARRAYAGG(JSON_OBJECT('id', g.id,'name', g.name,'x', g.x, 'y', g.y, 'map', g.`map`) ), '[]') FROM goal g WHERE g.`map` = m.id)as 'goals'
		FROM `map` m
	        WHERE m.id= ?;";
        private $getMapWithGoals = "SELECT g.id, g.name, g.x, g.y, g.map FROM goal g WHERE g.map = ?;";

        private $getMapStrategies = "SELECT s.id, s.x, s.y, s.name, s.goal_tgt, s.goal_src, (SELECT IFNULL(JSON_ARRAY(mc.id), '[]') FROM method_chunk mc WHERE mc.strategy = s.id) as 'methodChunkIds' FROM strategy s, goal g WHERE s.goal_tgt = g.id AND g.map = ?;";
        // %1\$s = id, %2\$s = name, %3\$s = repository
        private $addNewMap = "INSERT INTO map (id, name, repository) VALUES (?, ?, ?);";
        private $addGoal = "INSERT INTO goal (name, x, y, map) VALUES (?, ?, ?, ?);";
        private $updateMapStart = "UPDATE map SET start = (SELECT g.id FROM goal g WHERE g.map = ? AND g.name = 'Start') WHERE id = ?;";
        private $updateMapStop = "UPDATE map SET stop = (SELECT g.id FROM goal g WHERE g.map = ? AND g.name = 'Stop') WHERE id = ?;";

        private $updateMap = "UPDATE map SET name = ?, repository = ? WHERE id = ?";


        
        public function getAllMaps($repository) {
            $statement = $this->conn->prepare($this->getAllMaps);
            $statement->bind_param('s', $repository);
            return $this->executeSelectQuery($statement);
        }


        public function getMap($id) {
            $statement = $this->conn->prepare($this->getMap);
            $statement->bind_param('s', $id);
            return $this->executeSelectQuery($statement);
        }

        public function getFullMap($id) {
            $statement = $this->conn->prepare($this->getFullMap);
            $statement->bind_param('s', $id);
            return $this->executeSelectQuery($statement);
        }

        public function getMapWithGoals($id) {
            $statement = $this->conn->prepare($this->getMapWithGoals);
            $statement->bind_param('s', $id);
            return $this->executeSelectQuery($statement);
        }

        public function getMapStrategies($id) {
            $statement = $this->conn->prepare($this->getMapStrategies);
            $statement->bind_param('s', $id);
            return $this->executeSelectQuery($statement);
        }

        public function deleteMap($id) {
            $statement = $this->conn->prepare($this->deleteMap);
            $statement->bind_param('s', $id);
            return $this->executeDeleteQuery($statement);
        }

        public function addNewMap($id, $name, $repository) {
            $this->conn->begin_transaction();
            $result = "{\"error\": \"Result not set\", \"message\": \"Result not set\"}";
            try {

                $statement = $this->conn->prepare($this->addNewMap);
                $statement->bind_param('sss', $id, $name, $repository);
                if (!$statement->execute()) 
                    throw new Exception("Error in adding new map");
                
                    // START
                $statement = $this->conn->prepare($this->addGoal);
                $start = 'Start';
                $x = '-200';
                $y = '0';
                $statement->bind_param('ssss', $start, $x, $y, $id);
                if (!$statement->execute()) 
                    throw new Exception("Error creating 'Start' goal");
                
                $statement = $this->conn->prepare($this->updateMapStart);
                $statement->bind_param('ss', $id, $id);
                if (!$statement->execute()) 
                    throw new Exception("Error updating map's 'Start' goal");
                    
                // STOP
                $statement = $this->conn->prepare($this->addGoal);
                $stop = 'Stop';
                $x = '200';
                $statement->bind_param('ssss', $stop, $x, $y, $id);
                if (!$statement->execute()) 
                    throw new Exception("Error creating 'Stop' goal");
                
                $statement = $this->conn->prepare($this->updateMapStop);
                $statement->bind_param('ss', $id, $id);
                if (!$statement->execute()) 
                    throw new Exception("Error updating map's 'Stop' goal");
                
                $this->conn->commit();

                $statement = $this->conn->prepare($this->getFullMap);
                $statement->bind_param('s', $id);
                $result = $this->executeSelectQuery($statement)[0];
            }
            catch(Exception $e) {
                $this->conn->rollback();
                $result = "{\"error\": \"Error in adding new map\", \"message\": \"$e\"}";
            }
            return $result;
        }

        public function updateMap($name, $id, $repository) {
            $statement = $this->conn->prepare($this->updateMap);
            $statement->bind_param('sss', $name, $repository, $id );
            return $this->executeInsertQuery($statement);
        }
    }

?>