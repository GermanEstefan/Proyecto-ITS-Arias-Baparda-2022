<?php
    require_once("./database/Connection.php");
    class User extends Connection {

        protected $email;
        protected $name;
        protected $surname;
        protected $phone;
        protected $password;
        protected $address;
        protected $connection;

        function __construct($email, $name, $surname, $phone, $password, $address){
            $this->email = $email;
            $this->name = $name;
            $this->surname = $surname;
            $this->password = $password;
            $this->phone = $phone;
            $this->address = $address;
            $this->connection = new Connection();
        }

        public static function getUserByEmail($email){
            $conecction = new Connection();
            $query = "SELECT * from user WHERE email='$email'";
            return $conecction->getData($query)->fetch_assoc();
        }

        public static function getUserById($id){
            $conecction = new Connection();
            $query = "SELECT * from user WHERE id_user='$id'";
            return $conecction->getData($query)->fetch_assoc();
        }
    }
        
?>