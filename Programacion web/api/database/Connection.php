<?php
    class Connection {
        
        private $server;
        private $user;
        private $password;
        private $database;
        private $port;
        private $connection;

        public function __construct() {
            $data = $this->setConfig();
            $this->server = $data['server'];
            $this->user = $data['user'];
            $this->password = $data['password'];
            $this->database = $data['database'];
            $this->port = $data['port'];
            
            $this->connection = new mysqli($this->server, $this->user, $this->password, $this->database, $this->port);
            if($this->connection->connect_errno){
                echo "Algo va mal con la conexion!";
                die();
            }
        }

        private function setConfig(){
            $pathOfFile = dirname(__FILE__);
            $dataOfConfigInJson = file_get_contents($pathOfFile . "/" . "config.txt");
            return json_decode($dataOfConfigInJson, true);
        }

        public function getData($query){
            return $this->connection->query($query);
        }

        public function setData($query){
            return $this->connection->query($query);
        }

        public function getLastIdInserted(){
            return $this->connection->insert_id;
        }

        public function getInstance(){
            return $this->connection;
        }

        public function setDataByTransacction($querys){
            $result_transaccion = true;
            $this->connection->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            foreach ($querys as $query) { 
                $result = $this->setData($query);
                echo $result;
                if(!$result){
                    $result_transaccion = false;
                }
            }
            if($result_transaccion){
                $this->connection->commit();
            }else{
                $this->connection->rollback();
            }
            return $result_transaccion;
        }
    }
?>

