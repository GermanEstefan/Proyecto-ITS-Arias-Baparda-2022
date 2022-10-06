<?php
    require_once("./database/Connection.php");
    
    class UserModel{

        protected $email;
        protected $name;
        protected $surname;
        protected $password;
        protected $phone;
        protected $address;
        protected $conecction;

        function __construct($email, $name, $surname, $password, $phone, $address ){
            $this->email = $email;
            $this->name = $name;
            $this->surname = $surname;
            $this->password = $password;
            $this->phone = $phone;
            $this->address = $address;
            $this->conecction = new Connection();
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

        public static function updateUser($id, $name, $surname, $address, $phone){
            $conecction = new Connection();
            $query = "UPDATE user SET name = '$name', surname = '$surname', address = '$address', phone = '$phone' WHERE id_user = $id ";
            return $conecction->setData($query);
        }
                
        public function save(){
            $userInsert = "INSERT INTO user(email, name, surname, password) VALUES ('$this->email', '$this->name', '$this->surname', '$this->password' )";
            $result = $this->conecction->setData($userInsert);
            if($result){
                return $this->conecction->getLastIdInserted();
            }else{
                return false;
            }
        }
    }
        
?>