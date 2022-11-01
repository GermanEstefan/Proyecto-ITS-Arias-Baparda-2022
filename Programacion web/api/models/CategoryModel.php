<?php
    require_once("./database/Connection.php");
    class CategoryModel extends Connection {

        private $name;
        private $description;
        private $picture;

        function __construct($name, $description, $picture){
            $this->name = $name;
            $this->description = $description;
            $this->picture = $picture;
            parent::__construct();
        }

        public static function getCategoryByName($name){
            $conecction = new Connection();
            $query = "SELECT * from category WHERE name='$name'";
            return $conecction->getData($query)->fetch_assoc();
        }
        public static function getNameByIdCategory($idCategory){
            $conecction = new Connection();
            $query = "SELECT name from category WHERE id_category ='$idCategory'";
            return $conecction->getData($query)->fetch_assoc();
        }

        public static function getCategoryById($idCategory){
            $conecction = new Connection();
            $query = "SELECT * from category WHERE id_category='$idCategory'";
            return $conecction->getData($query)->fetch_assoc();
        }

        public static function getAllCategorys(){
            $conecction = new Connection();
            $query = "SELECT * from category ";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }

        public static function updateCategory($idCategory,$name, $description, $picture){
            $conecction = new Connection();
            $query = "UPDATE category SET name = '$name', description = '$description', picture = '$picture' WHERE id_category = '$idCategory' ";
            return $conecction->setData($query);
        }

        public static function updateCategoryNotName($idCategory, $description, $picture){
            $conecction = new Connection();
            $query = "UPDATE category SET description = '$description', picture = '$picture' WHERE id_category = '$idCategory' ";
            return $conecction->setData($query);
        }

        public static function deleteCategory($idCategory){
            $conecction = new Connection();
            $query = "DELETE FROM category WHERE id_category = '$idCategory'";
            return $conecction->setData($query);
        }
                
        public function save(){
            $categoryInsert = "INSERT INTO category (name, description,picture) VALUES ('$this->name', '$this->description', '$this->picture' )";
            $result = parent::setData($categoryInsert);
            if(!$result){
                return false;
            }
            return true;
        }
    }
        
?>