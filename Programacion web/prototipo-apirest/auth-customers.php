<?php
include('./helpers/Response.php');
include("./models/User.php");
include("./models/Customer.php");

$response = new Response(); //Esta instancia va a ser utilizado a lo largo del controlador para las respuestas.
header('Content-Type: application/json'); //Le decimos al agente que consuma el servidor que vamos a devolver JSON.

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $bodyOfRequest = file_get_contents('php://input'); //Obtiene el body de la request sin procesar(JSON).
    $userData = json_decode($bodyOfRequest, 1); //Transforma el JSON en un array asosciativo.

    if( !isset($_GET['url']) ){
        echo $response->error400();
        die();
    }

    $url = $_GET['url']; //Este valor sirve para diferenciar la accion(login, registro, etc). 
    switch ($url) {
        case 'login':
            $response->error200("Funcion en proceso je"); 
            die();
        case 'register':
            if (
                !isset($userData['email']) ||
                !isset($userData['name']) ||
                !isset($userData['surname']) ||
                !isset($userData['phone']) ||
                !isset($userData['password']) ||
                !isset($userData['address']) || 
                !isset($userData['typeCustomer']) ||
                !isset($userData['company']) || 
                !isset($userData['rut'])
            ) {
                http_response_code(400);
                echo $response->error400();
                die();
            }

            $mail = $userData['email'];
            $name = $userData['name'];
            $surname = $userData['surname'];
            $phone = $userData['phone'];
            $password = $userData['password'];
            $address = $userData['address'];
            $typeCustomer = $userData['typeCustomer'];
            $company = $userData['company'];
            $rut = $userData['rut'];

            $formValid = true; //Esta bandera es para verificar que el formulario sea valido.

            foreach ($userData as $value) { //Valida que ningun dato venga nulo y que no sean string vacios.
                if (empty($value)) $formValid = false;
            }

            //Validamos que sea un email valido, ademas del nombre y apellido.
            if (!filter_var($mail, FILTER_VALIDATE_EMAIL) || !preg_match("/^[a-zA-z]*$/", $name) || !preg_match("/^[a-zA-z]*$/", $surname)) $formValid = false;
            
            //Validamos que el telefono sea un entero, la contraseña sea mayor a 5 digitos y que la direccion sea un string
            if (!is_int($phone) || !(strlen($password) > 6) || !is_string($address)) $formValid = false;

            //Si entro en algun if anterior, seignifica que tiene campos invalidos, por lo tanto:
            if (!$formValid) {
                http_response_code(400);
                echo $response->error400();
                die();
            }
    }

} else {
    //Metodo no permitido.
    echo $response->error405();
    die();
}
