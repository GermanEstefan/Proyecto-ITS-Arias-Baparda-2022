<?php
    require_once("./database/Connection.php");
    class Employee extends User{

        private $rol;
        private $ci;

        function __construct($email, $name, $surname, $phone, $password, $address, $rol, $ci){
            parent::__construct($email, $name, $surname, $phone, $password, $address);
            $this->rol = $rol;
            $this->ci = $ci;
        }

        public static function getEmployeeByCi($ci){
            $conecction = new Connection();
            $query = "SELECT * from employee WHERE ci='$ci'";
            return $conecction->getData($query)->fetch_assoc();
        }

        public static function getEmployeeByEmail($email){
            die();
        }

        public function verifyEmployeeExistence($ci){
            //Con el registro que nos devuelve, debemos validar con el email que no exista en la tabla Users tampoco.
            $query = "SELECT * from employee WHERE ci='$ci'";
            return $this->connection->getData($query);
        }

        public function save(){
            $userInsert = "INSERT INTO user(email, name, surname, phone, address, password) VALUES ('$this->email', '$this->name', '$this->surname', '$this->phone', '$this->address', '$this->password' )";
            $employeeInsert = "INSERT INTO employee(email, name_rol, ci) VALUES ('$this->email', '$this->rol', '$this->ci' )";
            $querys = array($userInsert, $employeeInsert); 
            return $this->connection->setDataByTransacction($querys);
        }
    }

?>