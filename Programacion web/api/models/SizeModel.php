<?php
    require_once("./database/Connection.php");
    class SizeModel extends Connection {

        private $name;
        private $description;

        function __construct($name, $description){
            $this->name = $name;
            $this->description = $description;
            parent::__construct();
        }

        public static function getSizeByName($name){
            $conecction = new Connection();
            $query = "SELECT * from size WHERE name='$name'";
            return $conecction->getData($query)->fetch_assoc();
        }

        public static function getSizeById($idSize){
            $conecction = new Connection();
            $query = "SELECT * from size WHERE id_size='$idSize'";
            return $conecction->getData($query)->fetch_assoc();
        }

        public static function getAllSizes(){
            $conecction = new Connection();
            $query = "SELECT * from size ";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }

        public static function updateSize($idSize,$name, $description ){
            $conecction = new Connection();
            $query = "UPDATE size SET name = '$name', description = '$description' WHERE id_size = '$idSize' ";
            return $conecction->setData($query);
        }

        public static function updateSizeNotName($idSize, $description, ){
            $conecction = new Connection();
            $query = "UPDATE size SET description = '$description' WHERE id_size = '$idSize' ";
            return $conecction->setData($query);
        }

        public static function deleteSize($idSize){
            $conecction = new Connection();
            $query = "DELETE FROM size WHERE id_size = '$idSize'";
            return $conecction->setData($query);
        }
                
        public function save(){
            $sizeInsert = "INSERT INTO size (name, description) VALUES ('$this->name', '$this->description')";
            $result = parent::setData($sizeInsert);
            if(!$result){
                return false;
            }
            return true;
        }
    }
        
?>