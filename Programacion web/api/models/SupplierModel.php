<?php
    require_once("./database/Connection.php");
    class SupplierModel extends Connection {

        private $rut;
        private $name;
        private $address;
        private $phone;

        function __construct($rut,$name,$address, $phone){
            $this->rut = $rut;
            $this->name = $name;
            $this->address = $address;
            $this->description = $phone;
            parent::__construct();
        }

        public static function getSupplierByName($name){
            $conecction = new Connection();
            $query = "SELECT * from supplier WHERE name='$name'";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }
        public static function getSupplierByRut($rut){
            $conecction = new Connection();
            $query = "SELECT * from supplier WHERE rut=$rut";
            return $conecction->getData($query)->fetch_assoc();
        }

        public static function getSupplierById($idSupplier){
            $conecction = new Connection();
            $query = "SELECT * from supplier WHERE id_supplier=$idSupplier";
            return $conecction->getData($query)->fetch_assoc();
        }

        public static function getAllSuppliers(){
            $conecction = new Connection();
            $query = "SELECT * from supplier ";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }
        public static function getAllSuppliersActive(){
            $conecction = new Connection();
            $query = "SELECT * from supplier where state = 1";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }
        public static function getAllSuppliersDisable(){
            $conecction = new Connection();
            $query = "SELECT * from supplier where state = 0";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }

        public static function updateSupplier($idSupplier,$rut,$name,$address, $phone){
            $conecction = new Connection();
            $query = "UPDATE supplier SET rut = '$rut', company_name = '$name',address = '$address',phone = '$phone', WHERE id_supplier = '$idSupplier' ";
            return $conecction->setData($query);
        }

        public static function updateSupplierNotRut($idSupplier,$name,$address, $phone){
            $conecction = new Connection();
            $query = "UPDATE supplier SET company_name = '$name',address = '$address',phone = '$phone', WHERE id_supplier = '$idSupplier' ";
            return $conecction->setData($query);
        }

        public static function disableSupplier($idSupplier){
            $conecction = new Connection();
            $query = "UPDATE supplier SET state = 0 WHERE id_supplier = '$idSupplier'";
            return $conecction->setData($query);
        }
        public static function activeSupplier($idSupplier){
            $conecction = new Connection();
            $query = "UPDATE supplier SET state = 1 WHERE id_supplier = '$idSupplier'";
            return $conecction->setData($query);
        }
                
        public function save(){
            $supplierInsert = "INSERT INTO supplier (rut, company_name, address, phone) VALUES ('$this->rut','$this->name', '$this->address','$this->phone')";
            $result = parent::setData($supplierInsert);
            if(!$result){
                return false;
            }
            return true;
        }
    }
        
?>