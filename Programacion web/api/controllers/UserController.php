<?php

include('./helpers/Response.php');

class UserController {

    public static function validateUserRequest($userData){

        $response = new Response();

        $formValid = true; //Esta bandera es para verificar que el formulario sea valido.
            if (
                !isset($userData['email']) ||
                !isset($userData['name']) ||
                !isset($userData['surname']) ||
                !isset($userData['phone']) ||
                !isset($userData['password']) ||
                !isset($userData['address']) 
            ) {
                http_response_code(400);
                echo $response->error400();
                die();
            }

            foreach ($userData as $value) { //Valida string vacios.
                if (empty($value)) $formValid = false; 
            }
            
            //Validamos que sea un email valido, ademas del nombre y apellido.
            if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL) || !preg_match("/^[a-zA-z]*$/", $userData['name']) || !preg_match("/^[a-zA-z]*$/", $userData['surname'])) $formValid = false;
             
            //Validamos que el telefono sea un entero, la contraseña sea mayor a 5 digitos.
            if (!is_int($userData['phone']) || !(strlen($userData['password']) > 6) || !is_string($userData['address'])) $formValid = false;
            
            //Si entro en algun if anterior, seignifica que tiene campos invalidos, por lo tanto:
            if (!$formValid) {
                http_response_code(400);
                echo $response->error400();
                die();
            }

        return $userData;
    }

}
?>