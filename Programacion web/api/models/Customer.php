<?php

    class Customer extends User{

        private $typeCustomer;
        private $company;
        private $nRut;  

        function __construct($email, $name, $surname, $phone, $password, $address, $typeCustomer, $company, $nRut){
            parent::__construct($email, $name, $surname, $phone, $password, $address);
            $this->typeCustomer = $typeCustomer;
            $this->company = $company;
            $this->nRut = $nRut;
        }

        public static function getCustomerByEmail($email){
            $conecction = new Connection();
            $query = "SELECT * from customer WHERE email='$email'";
            return $conecction->getData($query)->fetch_assoc();
        }
        
        public function save(){
            //Hay que aplicar una TRANSACCION(PENDIENTE)
            $userInsert = "INSERT INTO user(email, name, surname, phone, address, password) VALUES ('$this->email', '$this->name', '$this->surname', '$this->phone', '$this->address', '$this->password' )";
            $customerInsert = "INSERT INTO customer(email, type_customer, company, rut) VALUES ('$this->email', '$this->typeCustomer', '$this->company', '$this->nRut')";
            $querys = array($userInsert, $customerInsert); 
            return $this->connection->setDataByTransacction($querys);
        }

    }

?>