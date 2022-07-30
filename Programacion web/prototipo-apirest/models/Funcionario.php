<?php
    
    class Funcionario extends Usuario{

        private $sueldo;
        private $estado;
        private $rol;

        function __construct($ci, $nombre, $apellido, $email, $password, $telefono, $direccion,$sueldo, $estado, $rol){
            parent::__construct($ci, $nombre, $apellido, $email, $password,$telefono, $direccion);
            $this->sueldo = $sueldo;
            $this->estado = $estado;
            $this->rol = $rol;
        }

        //Invocar a al instancia de la BD y definir funcion para dar de alta un usuario
    }

?>