<?php
    require_once("./database/Connection.php");
    class Employee extends User{

        private $rol;
        private $ci;

        function __construct($mail, $name, $surname, $phone, $password, $address, $rol, $ci){
            parent::__construct($mail, $name, $surname, $phone, $password, $address);
            $this->rol = $rol;
            $this->ci = $ci;
        }

        public function getEmployee($ci){
            $query = "SELECT * from funcionario WHERE ci='$ci'";
            return $this->connection->getData($query);
        }

        public function save(){
            $userInsert = "INSERT INTO usuario(mail, nombre, apellido, telefono, direccion, password) VALUES ('$this->mail', '$this->name', '$this->surname', '$this->phone', '$this->address', '$this->password' )";
            $employeeInsert = "INSERT INTO funcionario(mail, ci, IDR) VALUES ('$this->mail', '$this->ci', '$this->rol' )";
            $querys = array($userInsert, $employeeInsert); 
            return $this->connection->setDataByTransacction($querys);
        }
    }

?>