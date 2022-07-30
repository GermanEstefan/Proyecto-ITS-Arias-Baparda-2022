<?php
    class Usuario {

        private $email;
        private $nombre;
        private $apellido;
        private $telefono;
        private $password;
        private $direccion;

        function __construct($ci, $nombre, $apellido, $email, $password, $telefono, $direccion){
            $this->ci = $ci;
            $this->nombre = $nombre;
            $this->apellido = $apellido;
            $this->email = $email;
            $this->password = $password;
            $this->telefono = $telefono;
            $this->direccion = $direccion;
        }

        //Invocar a al instancia de la BD y definir funcion para dar de alta un usuario
    }
        
?>