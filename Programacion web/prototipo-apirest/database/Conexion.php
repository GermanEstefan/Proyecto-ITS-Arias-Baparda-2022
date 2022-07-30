<?php
    class Conexion {
        
        private $server;
        private $user;
        private $password;
        private $database;
        private $port;
        public $conexion;

        public function __construct() {
            $data = $this->setConfig();
            $this->server = $data['server'];
            $this->user = $data['user'];
            $this->password = $data['password'];
            $this->database = $data['database'];
            $this->port = $data['port'];
            
            $this->conexion = new mysqli($this->server, $this->user, $this->password, $this->database, $this->port);
            if($this->conexion->connect_errno){
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
            $arrayFromResults = array();
            $results = $this->conexion->query($query);
            while($row = $results->fetch_assoc()){
                array_push($arrayFromResults, $row);
            }
            return json_encode($arrayFromResults);
        }
    }
?>