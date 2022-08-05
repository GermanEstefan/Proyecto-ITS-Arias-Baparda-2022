<?php
    class User {

        protected $mail;
        protected $name;
        protected $surname;
        protected $phone;
        protected $password;
        protected $address;
        protected $connection;

        function __construct($mail, $name, $surname, $phone, $password, $address){
            $this->mail = $mail;
            $this->name = $name;
            $this->surname = $surname;
            $this->password = $password;
            $this->phone = $phone;
            $this->address = $address;
            $this->connection = new Connection();
        }
    }
        
?>