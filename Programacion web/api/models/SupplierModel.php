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
            parent::__construct();
        }

        //sacar todos los proveedores
        public static function getAllSuppliers(){
            $conecction = new Connection();
            $query = "SELECT * FROM supplier";
            return $conecction->getData($query)->fetch_all();
        }

        //sacar proveedores activos
        public static function getActiveSuppliers(){
            $connection = new Connection();
            $query = "SELECT * FROM supplier WHERE state='1'";
            return $connection->getData($query)->fetch_all();
        }

        //sacar proveedor activo por rut
        public static function getActiveSupplierByRut($rut){
            $connection = new Connection();
            $query = "SELECT * FROM supplier WHERE state='1' AND rut='$rut'";
            return $connection->getData($query)->fetch_assoc();
        }

        //sacar proveedores dados de baja
        public static function getDisabledSuppliers(){
            $connection = new Connection();
            $query = "SELECT * FROM supplier WHERE state='0'";
            return $connection->getData($query)->fetch_all();
        }

        //sacar proveedor dados de baja por RUT
        public static function getDisabledSupplierByRut($rut){
            $connection = new Connection();
            $query = "SELECT * FROM supplier WHERE state='0' AND rut='$rut'";
            return $connection->getData($query)->fetch_assoc();
        }
        
        //sacar proveedores por numero de rut
        public static function getSupplierByRut($rut){
            $conecction = new Connection();
            $query = "SELECT * from supplier WHERE rut='$rut'";
            return $conecction->getData($query)->fetch_assoc();
        }

        //sacar provedores por nombre
        public static function getSupplierByName($company){
            $conecction = new Connection();
            $query = "SELECT * from supplier WHERE company_name='$company'";
            return $conecction->getData($query)->fetch_assoc();
        }

        //update de proveedor
        public static function updateSupplier($rut, $company, $address, $phone){
            $conecction = new Connection();
            $query = "UPDATE supplier SET rut = '$rut', company = '$company', address = '$address', phone = '$phone' WHERE rut = $rut ";
            return $conecction->setData($query);
        }

        //deshabilitar proveedor
        public static function disableSupplier($rut){
            $conecction = new Connection();
            $query = "UPDATE supplier SET state = 0 WHERE rut = $rut ";
            return $conecction->setData($query);
        }        

        //habilitar proveedor
        public static function enableSupplier($rut){
            $connection = new Connection();
            $query = "UPDATE supplier SET STATE = 1 WHERE rut = $rut ";
            return $connection->setData($query);
        }

        //ingresar proveedor
        public function save(){
            $supplierInsert = "INSERT INTO supplier VALUES (null, '{$this->rut}', '{$this->company}', '{$this->phone}', '{$this->address}', null)";
            $result = parent::setData($supplierInsert);
            if(!$result){
                return false;
            }
            return true;
        }
    }
        
?>