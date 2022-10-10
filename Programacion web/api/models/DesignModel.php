<?php
    require_once("./database/Connection.php");
    class DesignModel extends Connection {

        private $name;
        private $description;

        function __construct($name, $description){
            $this->name = $name;
            $this->description = $description;
            parent::__construct();
        }

        public static function getDesignByName($name){
            $conecction = new Connection();
            $query = "SELECT * from design WHERE name='$name'";
            return $conecction->getData($query)->fetch_assoc();
        }

        public static function getDesignById($idDesign){
            $conecction = new Connection();
            $query = "SELECT * from design WHERE id_design='$idDesign'";
            return $conecction->getData($query)->fetch_assoc();
        }

        public static function getAllDesigns(){
            $conecction = new Connection();
            $query = "SELECT * from design ";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }

        public static function updateDesign($idDesign,$name, $description){
            $conecction = new Connection();
            $query = "UPDATE design SET name = '$name', description = '$description' WHERE id_design = '$idDesign' ";
            return $conecction->setData($query);
        }

        public static function updateDesignNotName($idDesign, $description){
            $conecction = new Connection();
            $query = "UPDATE design SET description = '$description' WHERE id_design = '$idDesign' ";
            return $conecction->setData($query);
        }

        public static function deleteDesign($idDesign){
            $conecction = new Connection();
            $query = "DELETE FROM design WHERE id_design = '$idDesign'";
            return $conecction->setData($query);
        }
                
        public function save(){
            $designInsert = "INSERT INTO design (name, description) VALUES ('$this->name', '$this->description')";
            $result = parent::setData($designInsert);
            if(!$result){
                return false;
            }
            return true;
        }
    }
        
?>