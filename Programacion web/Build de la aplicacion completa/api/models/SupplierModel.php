<?php
    require_once("./database/Connection.php");
    class SupplierModel extends Connection {

        protected $rut;
        protected $company;
        protected $address;
        protected $phone;
        protected $connection;

        function __construct($rut, $company, $address, $phone ){

            $this->rut = $rut;
            $this->company = $company;
            $this->addres = $address;
            $this->phone = $phone;
            $this->connection = new Connection();
        }

        public static function getSupplierByRut($rut){
            $conecction = new Connection();
            $query = "SELECT * from supplier WHERE rut='$rut'";
            return $conecction->getData($query)->fetch_assoc();
        }

        public static function getSupplierByName($company){
            $conecction = new Connection();
            $query = "SELECT * from supplier WHERE company_name='$company'";
            return $conecction->getData($query)->fetch_assoc();
        }

        public static function updateSupplier($rut, $company, $address, $phone){
            $conecction = new Connection();
            $query = "UPDATE supplier SET rut = '$rut', company = '$company', address = '$address', phone = '$phone' WHERE rut = $rut ";
            return $conecction->setData($query);
        }

        public static function DisableSupplier($rut){
            $conecction = new Connection();
            $query = "UPDATE supplier SET state = 0 WHERE rut = $rut ";
            return $conecction->setData($query);
        
        }        
        public function save(){
            $supplierInsert = "INSERT INTO supplier(rut, company, address, phone) VALUES ('$this->rut', '$this->compamny', '$this->address', '$this->phone' )";
            $result = $this->connection->setData($supplierInsert);
            if($result){
                return $this->connection->getLastIdInserted();
            }else{
                return false;
            }
        }
    }
        
?>