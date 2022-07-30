<?php

    class Cliente extends Usuario{
        private $tipoCli;
        private $empresa;
        private $nRut;  

        function __construct($ci, $nombre, $apellido, $email, $password, $telefono, $direccion,$tipoCli, $empresa, $nRut){
            parent::__construct($ci, $nombre, $apellido, $email, $password,$telefono, $direccion);
            $this->tipoCli = $tipoCli;
            $this->empresa = $empresa;
            $this->nRut = $nRut;
        }
        
        //Invocar a al instancia de la BD y definir funcion para dar de alta un usuario

    }

?>