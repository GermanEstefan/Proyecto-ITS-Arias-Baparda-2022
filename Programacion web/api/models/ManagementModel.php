<?php
    require_once("./database/Connection.php");
    class ManagementModel extends Connection {

        public static function getBalances(){
            $conecction = new Connection();
            $query = "SELECT 
            SUM(s.TOTAL) AS totalSale,
            SUM(sp.total) AS totalSupply
            FROM SALE s, supply sp";
            return $conecction->getData($query)->fetch_assoc();
        }

        public static function getAllManagement(){
            $conecction = new Connection();
            $query = "SELECT * from management";
            return $conecction->getData($query)->fetch_assoc();
        }

        public static function updateManagement($id, $name, $description){
            $conecction = new Connection();
            $query = "UPDATE management SET name = '$name', description = '$description' WHERE id_photo = '$id' ";
            return $conecction->setData($query);
        }

        public static function DisableManagement($id){
            $conecction = new Connection();
            $query = "UPDATE management SET state = 0 WHERE id_photo = $id ";
            return $conecction->setData($query);
        
        }        
        public function save(){
            $ManagementInsert = "INSERT INTO management (name, description) VALUES ('$this->name', '$this->description' )";
            $result = $this->connection->setData($ManagementInsert);
            if($result){
                return $this->connection->getLastIdInserted();
            }else{
                return false;
            }
        }
    }
        
?>