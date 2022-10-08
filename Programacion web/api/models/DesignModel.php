<?php
    require_once("./database/Connection.php");
    class DesignModel extends Connection {

        protected $name;
        protected $description;

        function __construct($name, $description){
            $this->name = $name;
            $this->description = $description;
            $this->connection = new Connection();
        }

        public static function getDesignByName($name){
            $conecction = new Connection();
            $query = "SELECT * from design WHERE name='$name'";
            return $conecction->getData($query)->fetch_assoc();
        }

        public static function getDesignById($id){
            $conecction = new Connection();
            $query = "SELECT * from design WHERE id_design='$id'";
            return $conecction->getData($query)->fetch_assoc();
        }
        
        public static function getAllDesign(){
            $conecction = new Connection();
            $query = "SELECT * from design ";
            return $conecction->getData($query)->fetch_all();
        }

        public static function updateDesign($id, $name, $description){
            $conecction = new Connection();
            $query = "UPDATE design SET name = '$name', description = '$description' WHERE id = $id";
            return $conecction->setData($query);
        }

        public static function deleteDesign($idDesign){
            $conecction = new Connection();
            $query = "DELETE FROM design WHERE id_design = '$idDesign'";
            return $conecction->setData($query);
        }
                
        public function save(){
            $designInsert = "INSERT INTO design (name, description) VALUES ('$this->name', '$this->description' )";
            $result = $this->connection->setData($designInsert);
            if($result){
                return $this->connection->getLastIdInserted();
            }else{
                return false;
            }
        }
    }
        
?>