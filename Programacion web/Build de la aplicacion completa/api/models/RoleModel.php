<?php
    require_once("./database/Connection.php");
    class RoleModel extends Connection {

        private $name;
        private $description;

        function __construct($name, $description){
            $this->name = $name;
            $this->description = $description;
        }

        public static function getRoleByName($name){
            $conecction = new Connection();
            $query = "SELECT * from role WHERE name_role='$name'";
            return $conecction->getData($query)->fetch_assoc();
        }


        public static function getAllRole(){
            $conecction = new Connection();
            $query = "SELECT * from role ";
            return $conecction->getData($query)->fetch_assoc();
        }

        public static function updateRole($name, $description){
            $conecction = new Connection();
            $query = "UPDATE role SET name_role = '$name', description = '$description' WHERE name_role = $name";
            return $conecction->setData($query);
        }
                
        public function save(){
            $roleInsert = "INSERT INTO role (name_role, description) VALUES ('$this->name', '$this->description' )";
            $result = $this->connection->setData($roleInsert);
            if($result){
                echo 'Rol ingresado exitosamente'; //CAMBIE ACA POR QUE NO DEVUELVE UN ID YA QUE LA TABLA ROLE SOLO TIENE NAME_ROLE Y DESCRIPCION
            }else{
                return false;
            }
        }
    }
        
?>