<?php
    require_once("./database/Connection.php");
    class StatusModel extends Connection {

        private $name;
        private $description;

        function __construct($name, $description){
            $this->name = $name;
            $this->description = $description;
            parent::__construct();
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
            $query = "SELECT id_status,name from status";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }

        public static function updateDescriptionStatus($id, $description){
            $conecction = new Connection();
            $query = "UPDATE status SET description = '$description' WHERE id_status = $id ";
            return $conecction->setData($query);
        }
                
        public function save(){
            $statusInsert = "INSERT INTO status (name, description) VALUES ('$this->name', '$this->description' )";
            $result = parent::setData($statusInsert);
            if(!$result){
                return false;
            }
            return true;
            
        }
    }
        
?>