<?php
    require_once("./database/Connection.php");
    class DeliveryModel extends Connection {

        private $name;
        private $description;

        function __construct($name, $description){
            $this->name = $name;
            $this->description = $description;
            parent::__construct();
        }

        public static function getDeliveryByName($name){
            $conecction = new Connection();
            $query = "SELECT * from delivery_time WHERE name='$name'";
            return $conecction->getData($query)->fetch_assoc();
        }

        public static function getDeliveryById($idDelivery){
            $conecction = new Connection();
            $query = "SELECT * from delivery_time WHERE id_delivery='$idDelivery'";
            return $conecction->getData($query)->fetch_assoc();
        }
        public static function validateDelivery($name){
            $conecction = new Connection();
            $query = "SELECT * from delivery_time WHERE name='$name'";
            return $conecction->getData($query)->fetch_assoc();
        }
        public static function getAllDeliverys(){
            $conecction = new Connection();
            $query = "SELECT * from delivery_time ";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }

        public static function updateDelivery($idDelivery,$name, $description ){
            $conecction = new Connection();
            $query = "UPDATE delivery_time SET name = '$name', description = '$description' WHERE id_delivery = '$idDelivery' ";
            return $conecction->setData($query);
        }

        public static function updateDeliveryNotName($idDelivery, $description){
            $conecction = new Connection();
            $query = "UPDATE delivery_time SET description = '$description' WHERE id_delivery = '$idDelivery' ";
            return $conecction->setData($query);
        }

        public static function deleteDelivery($idDelivery){
            $conecction = new Connection();
            $query = "DELETE FROM delivery_time WHERE id_delivery = '$idDelivery'";
            return $conecction->setData($query);
        }
                
        public function save(){
            $deliveryInsert = "INSERT INTO delivery_time (name, description) VALUES ('$this->name', '$this->description')";
            $result = parent::setData($deliveryInsert);
            if(!$result){
                return false;
            }
            return true;
        }
    }
        
?>