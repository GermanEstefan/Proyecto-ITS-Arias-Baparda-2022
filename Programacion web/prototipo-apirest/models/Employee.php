<?php
    require_once("./database/Connection.php");
    class Employee extends User{

        private $salary;
        private $rol;
        private $ci;

        function __construct($mail, $name, $surname, $phone, $password, $address, $salary, $rol, $ci){
            parent::__construct($mail, $name, $surname, $phone, $password, $address);
            $this->salary = $salary;
            $this->rol = $rol;
            $this->ci = $ci;
        }

        public function save(){
            //FOREIGN KEY DE FUNCIONARIO DEBE SER UNICA.
            //Hay que aplicar una TRANSACCION(PENDIENTE)
            $query1 = "INSERT INTO usuario(mail, nombre, apellido, telefono, direccion, password) VALUES ('$this->mail', '$this->name', '$this->surname', '$this->phone', '$this->address', '$this->password' )";
            $query2 = "INSERT INTO funcionario(mail, ci, sueldo, IDR) VALUES ('$this->mail', '$this->ci', '$this->salary', '$this->rol' )";
            //Si devuelve 11 es por que ambas se ejecutaron con exito
            echo $this->connection->setData($query1);
            echo $this->connection->setData($query2);
        }
    }

?>