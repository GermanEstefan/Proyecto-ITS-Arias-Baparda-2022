<?php
    require_once("./database/Connection.php");
    class SaleModel extends Connection {

        private $address;
        private $client;
        private $delivery;

        function __construct($address,$client,$delivery,){
            $this->name = $address;
            $this->description = $client;
            $this->description = $delivery;
            parent::__construct();
        }
        //CONSULTAS
        public static function getSaleById($id){
            $conecction = new Connection();
            $query = "SELECT * from sale WHERE id_sale='$id'";
            return $conecction->getData($query)->fetch_assoc();
        }
        public static function getAllSales(){
            $conecction = new Connection();
            $query = "SELECT * from sale";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }
        public static function getSalesByDate(){
            $conecction = new Connection();
            $query = "SELECT * from sale";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }
        public static function getSalesByClient($client){
            $conecction = new Connection();
            $query = "SELECT * from sale WHERE user_purchase = $client";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }
        public static function getSalesByDelivery($delivery){
            $conecction = new Connection();
            $query = "SELECT * from sale where sale_delivery = $delivery";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }
        public static function GetTotalSalesIncome(){
            $conecction = new Connection();
            $query = "SELECT SUM(total) from sale";
            return $conecction->getData($query)->fetch_assoc();
        }


        public static function updateDescriptionSale($id, $description){
            $conecction = new Connection();
            $query = "UPDATE sale SET description = '$description' WHERE id_sale = $id ";
            return $conecction->setData($query);
        }
                
        public function save(){
            $saleInsert = "INSERT INTO sale (name, description) VALUES ('$this->name', '$this->description' )";
            $result = parent::setData($saleInsert);
            if(!$result){
                return false;
            }
            return true;
            
        }
    }
        
?>