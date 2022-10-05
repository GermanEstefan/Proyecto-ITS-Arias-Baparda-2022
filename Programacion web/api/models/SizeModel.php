<?php
    require_once("./database/Connection.php");
    class SizeModel extends Connection {

        private $id;
        private $name;
        private $description;

        function __construct($id, $name, $description){
            $this->id = $id;
            $this->name = $name;
            $this->description = $description;
        }

        public static function getSizeByName($name){
            $conecction = new Connection();
            $query = "SELECT * from size WHERE name='$name'";
            return $conecction->getData($query)->fetch_assoc();
        }

        public static function getSizeById($id){
            $conecction = new Connection();
            $query = "SELECT * from size WHERE id_size='$id'";
            return $conecction->getData($query)->fetch_assoc();
        }

        public static function getAllSize(){
            $conecction = new Connection();
            $query = "SELECT * from size ";
            return $conecction->getData($query)->fetch_assoc();
        }

        public static function updateSize($id, $name, $description){
            $conecction = new Connection();
            $query = "UPDATE size SET name = '$name', description = '$description' WHERE id_size = $id ";
            return $conecction->setData($query);
        }
                
        public function save(){
            $sizeInsert = "INSERT INTO size (id_size, name, description) VALUES ('$this->id','$this->name', '$this->description' )";
            $result = $this->connection->setData($sizeInsert);
            if($result){
                return $this->connection->getLastIdInserted();
            }else{
                return false;
            }
        }
    }
        
?>