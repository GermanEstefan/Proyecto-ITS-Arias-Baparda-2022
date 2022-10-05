<?php
    require_once("./database/Connection.php");
    class CategoryModel extends Connection {

        protected $name;
        protected $description;

        function __construct($name, $description){
            $this->name = $name;
            $this->description = $description;
            $this->connection = new Connection();
        }

        public static function getCategoryByName($name){
            $conecction = new Connection();
            $query = "SELECT * from category WHERE name='$name'";
            return $conecction->getData($query)->fetch_assoc();
        }

        public static function getCategoryById($id){
            $conecction = new Connection();
            $query = "SELECT * from category WHERE id_category='$id'";
            return $conecction->getData($query)->fetch_assoc();
        }

        public static function getAllCategory(){
            $conecction = new Connection();
            $query = "SELECT * from category ";
            return $conecction->getData($query)->fetch_assoc();
        }

        public static function updateCategory($id, $name, $description){
            $conecction = new Connection();
            $query = "UPDATE category SET name = '$name', description = '$description' WHERE id_category = $id ";
            return $conecction->setData($query);
        }
                
        public function save(){
            $categoryInsert = "INSERT INTO category (name, description) VALUES ('$this->name', '$this->description' )";
            $result = $this->connection->setData($categoryInsert);
            if($result){
                return $this->connection->getLastIdInserted();
            }else{
                return false;
            }
        }
    }
        
?>