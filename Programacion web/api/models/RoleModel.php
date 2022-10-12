<?php
    require_once("./database/Connection.php");
    class RoleModel extends Connection {

        private $name;
        private $description;

        function __construct($name, $description){
            $this->name = $name;
            $this->description = $description;
            parent::__construct();
        }

        public static function getRoleByName($name){
            $conecction = new Connection();
            $query = "SELECT * from role WHERE name_role='$name'";
            return $conecction->getData($query)->fetch_assoc();
        }
        
        public static function getAllRoles(){
            $conecction = new Connection();
            $query = "SELECT * from role ";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }

        public static function updateDescriptionRole($name,$description){
            $conecction = new Connection();
            $query = "UPDATE role SET description = '$description' WHERE name_role = '$name' ";
            return $conecction->setData($query);
        }

        public static function deleteRole($name){
            $conecction = new Connection();
            $query = "DELETE FROM role WHERE name_role = '$name'";
            return $conecction->setData($query);
        }
                
        public function save(){
            $roleInsert = "INSERT INTO role (name_role, description) VALUES ('$this->name', '$this->description')";
            $result = parent::setData($roleInsert);
            if(!$result){
                return false;
            }
            return true;
        }
    }
        
?>