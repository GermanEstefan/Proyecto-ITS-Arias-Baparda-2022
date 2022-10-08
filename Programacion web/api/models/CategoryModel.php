<?php
    require_once("./database/Connection.php");
    class CategoryModel extends Connection {

        private $name;
        private $description;

        function __construct($name, $description){
            $this->name = $name;
            $this->description = $description;
            parent::__construct();
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

        public static function getAllCategorys(){
            $conecction = new Connection();
            $query = "SELECT * from category ";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }

        public static function updateCategory($name, $description){
            $conecction = new Connection();
            $query = "UPDATE category SET name = '$name', description = '$description' WHERE name = '$name' ";
            return $conecction->setData($query);
        }
                
        public function save(){
            $categoryInsert = "INSERT INTO category (name, description) VALUES ('$this->name', '$this->description' )";
            $result = parent::setData($categoryInsert);
            if(!$result){
                return false;
            }
            return true;
        }
    }
        
?>