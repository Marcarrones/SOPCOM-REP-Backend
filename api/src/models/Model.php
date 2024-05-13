<?php

use OpenApi\Loggers\ConsoleLogger;

    require $_SERVER['DOCUMENT_ROOT'] . '/config/config.php';

    class Model {

        protected $conn;

        function __construct()
        {
            global $config;
            $credentials = $config['database'];
            try{
                $this->conn = new mysqli($credentials['host'], $credentials['user'], $credentials['pw'], $credentials['bd'], $credentials['port']);
            } catch (Exception $e) {
                echo(json_encode(Array('error' => "Database connection failed")));
                header("Content-Type: application/json");
                http_response_code(400);
            }
        }

        function __destruct() {
            $this->conn->close();
        }
        
        protected function executeSelectQuery($statement) {
            try {
                $result = [];
                $statement->execute();
                $rows = $statement->get_result();
                while($row = $rows->fetch_assoc()) {
                    $result[] = $row;
                }
                return $result;
            } catch (Exception $e) {
                return Array('error' => $statement->error);
            }
        }

        protected function executeDeleteQuery($statement) {
            try {
                $statement->execute();
                $error = $statement->errno;
                return $error;
            } catch (Exception $e) {
                return Array('error' => $statement->error);
            }
        }

        protected function executeInsertQuery($statement) {
            try {
                $statement->execute();
                $error = $statement->errno;
                if($error === 0) {
                    return $statement->insert_id;
                } else {
                    return Array('error' => "Database error");
                }
            } catch (Exception $e) {
                return Array('error' => $statement->error);
            }
        }

        protected function executeUpdateQuery($statement) {
            try {
                $statement->execute();
                $error = $statement->errno;
                return $error;
            } catch (Exception $e) {
                return Array('error' => $statement->error);
            }
        }
    }
    
?>