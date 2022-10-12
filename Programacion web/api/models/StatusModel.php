<?php
    require_once("./database/Connection.php");
    class StatusModel extends Connection {

        private $name;
        private $description;

        function __construct($name, $description){
            $this->name = $name;
            $this->description = $description;
        }

        public static function getStatusByName($name){
            $conecction = new Connection();
            $query = "SELECT * from status WHERE name='$name'";
            return $conecction->getData($query)->fetch_assoc();
        }

        public static function getStatusById($id){
            $conecction = new Connection();
            $query = "SELECT * from status WHERE id_status='$id'";
            return $conecction->getData($query)->fetch_assoc();
        }

        public static function getAllStatus(){
            $conecction = new Connection();
            $query = "SELECT * from status ";
            return $conecction->getData($query)->fetch_assoc();
        }

        public static function updateStatus($id, $name, $description){
            $conecction = new Connection();
            $query = "UPDATE status SET name = '$name', description = '$description' WHERE id_status = $id ";
            return $conecction->setData($query);
        }
                
        public function save(){
            $statusInsert = "INSERT INTO status (name, description) VALUES ('$this->name', '$this->description' )";
            $result = $this->connection->setData($statusInsert);
            if($result){
                return $this->connection->getLastIdInserted();
            }else{
                return false;
            }
        }
    }
        
?>