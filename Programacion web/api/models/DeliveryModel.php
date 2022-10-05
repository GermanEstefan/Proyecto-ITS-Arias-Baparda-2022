<?php
    require_once("./database/Connection.php");
    class DeliveryModel extends Connection {

        protected $name;
        protected $description;
        protected $connection;

        function __construct($name, $description ){

            $this->name = $name;
            $this->description = $description;
            $this->connection = new Connection();
        }

        public static function getDeliveryByname($name){
            $conecction = new Connection();
            $query = "SELECT * from delivery_time WHERE name='$name'";
            return $conecction->getData($query)->fetch_assoc();
        }

        public static function getAllDelivery(){
            $conecction = new Connection();
            $query = "SELECT * from delivery_time";
            return $conecction->getData($query)->fetch_assoc();
        }

        public static function updateDelivery($id, $name, $description){
            $conecction = new Connection();
            $query = "UPDATE delivery_time SET name = '$name', description = '$description' WHERE id_delivery = '$id' ";
            return $conecction->setData($query);
        }

        public static function DisableDelivery($id){
            $conecction = new Connection();
            $query = "UPDATE delivery_time SET state = 0 WHERE id_delivery = $id ";
            return $conecction->setData($query);
        
        }        
        public function save(){
            $DeliveryInsert = "INSERT INTO delivery_time (name, description) VALUES ('$this->name', '$this->description' )";
            $result = $this->connection->setData($DeliveryInsert);
            if($result){
                return $this->connection->getLastIdInserted();
            }else{
                return false;
            }
        }
    }
        
?>