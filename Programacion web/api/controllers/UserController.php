<?php

class UserController{

    public static function validateBodyOfRegisterUser($userData){

        $formValid = true; //Esta bandera es para verificar que el formulario sea valido.

        if (
            !isset($userData['email']) ||
            !isset($userData['name']) ||
            !isset($userData['surname']) ||
            !isset($userData['password']) 
        ) $formValid = false;

        //Validamos que sea un email valido.
        if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) $formValid = false;

        //Validamos que el telefono sea un entero, la contraseña sea mayor a 5 digitos.
        if (!(strlen($userData['password']) > 5)) $formValid = false;
     
        if (!$formValid) {
            return false;
        } else {
            return $userData;
        }

    }

    public static function validateBodyOfUpdateUser($userData){

        if (
            !isset($userData['name']) ||
            !isset($userData['surname']) ||
            !isset($userData['address']) ||
            !isset($userData['phone']) 
        ) {
            return false;
        }else{
            return $userData;
        }

    }
}
