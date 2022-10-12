<?php
    require_once("./database/Connection.php");
    class DeliveryTimeModel extends Connection {

        private $name;
        private $description;

        function __construct($name, $description){
            $this->name = $name;
            $this->deliveryTime = $description;
            parent::__construct();
        }

        public static function getDeliveryTimeById($idDeliveryTime){
            $conecction = new Connection();
            $query = "SELECT * from delivery_time WHERE id_delivery = '$idDeliveryTime'";
            return $conecction->getData($query)->fetch_assoc();
        }

        public static function getAllDeliveryTime(){
            $conecction = new Connection();
            $query = "SELECT * from delivery_time ";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }

        public static function updateDeliveryTime($idDeliveryTime,$name, $description){
            $conecction = new Connection();
            $query = "UPDATE delivery_time SET name = '$name', description = '$description' WHERE id_delivery = '$idDeliveryTime' ";
            return $conecction->setData($query);
        }

        public static function deleteDeliveryTime($idDeliveryTime){
            $conecction = new Connection();
            $query = "DELETE FROM delivery_time WHERE id_delivery = '$idDeliveryTime'";
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