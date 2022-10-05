<?php
    require_once("./database/Connection.php");
    class PhotosModel extends Connection {

        protected $name;
        protected $description;
        protected $connection;

        function __construct($name, $description ){

            $this->name = $name;
            $this->description = $description;
            $this->connection = new Connection();
        }

        public static function getPhotosByname($name){
            $conecction = new Connection();
            $query = "SELECT * from photos WHERE name='$name'";
            return $conecction->getData($query)->fetch_assoc();
        }

        public static function getAllPhotos(){
            $conecction = new Connection();
            $query = "SELECT * from photos";
            return $conecction->getData($query)->fetch_assoc();
        }

        public static function updatePhotos($id, $name, $description){
            $conecction = new Connection();
            $query = "UPDATE photos SET name = '$name', description = '$description' WHERE id_photo = '$id' ";
            return $conecction->setData($query);
        }

        public static function DisablePhotos($id){
            $conecction = new Connection();
            $query = "UPDATE photos SET state = 0 WHERE id_photo = $id ";
            return $conecction->setData($query);
        
        }        
        public function save(){
            $PhotosInsert = "INSERT INTO photos (name, description) VALUES ('$this->name', '$this->description' )";
            $result = $this->connection->setData($PhotosInsert);
            if($result){
                return $this->connection->getLastIdInserted();
            }else{
                return false;
            }
        }
    }
        
?>