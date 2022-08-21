<?php

class UserController{

    public static function validateBodyOfRegisterUser($userData){

        $formValid = true; //Esta bandera es para verificar que el formulario sea valido.

        if (
            !isset($userData['email']) ||
            !isset($userData['name']) ||
            !isset($userData['surname']) ||
            !isset($userData['phone']) ||
            !isset($userData['password']) ||
            !isset($userData['address'])
        ) $formValid = false;

        //Validamos que sea un email valido.
        if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) $formValid = false;

        //Validamos que el telefono sea un entero, la contraseña sea mayor a 5 digitos.
        if (!(strlen($userData['password']) > 6) || !is_string($userData['address'])) $formValid = false;

        if (!$formValid) {
            return false;
        } else {
            return $userData;
        }

    }
}
