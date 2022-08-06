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
        
        public function save(){
            //Hay que aplicar una TRANSACCION(PENDIENTE)
            $query1 = "INSERT INTO user(email, name, surname, phone, address, password) VALUES ('$this->email', '$this->name', '$this->surname', '$this->phone', '$this->address', '$this->password' )";
            $query2 = "INSERT INTO customer(email, type_customer, company, rut) VALUES ('$this->mail', '$this->typeCustomer', '$this->company', '$this->nRut')";
            //Si devuelve 11 es por que ambas se ejecutaron con exito
            echo $this->connection->setData($query1);
            echo $this->connection->setData($query2);
        }

    }

?>