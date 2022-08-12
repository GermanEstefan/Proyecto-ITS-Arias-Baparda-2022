<?php
include('./helpers/Response.php');
include("./models/User.php");
include("./models/Customer.php");
include("./helpers/Token.php");

$response = new Response(); //Esta instancia va a ser utilizado a lo largo del controlador para las respuestas.
$jwt = new Token();
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
            if(!isset($userData['email']) || !isset($userData['password'])){
                http_response_code(400);
                echo $response->error400();
                die();
            }

            $email = $userData['email'];
            $password = $userData['password'];

            $customerExistInDatabase = Customer::getCustomerByEmail($email);
            if(!$customerExistInDatabase){
                http_response_code(200);
                echo $response->error200("El cliente con el email: $email no existe");
                die();
            }

            $customerEmail = $customerExistInDatabase['email'];
            $userInDatabase = User::getUserByEmail($customerEmail);
            $userInDatabasePassword = $userInDatabase['password'];

            if( !($password == $userInDatabasePassword) ){
                http_response_code(401);
                echo $response->error401('Credenciales incorrectas');
                die();
            }

            /*Aca hay que generar un token con los datos del usuario y mandarselos al cliente, para que luego, en cada request se mande ese token para validar*/
            $userToken = $jwt->generateToken($customerEmail);
            $bodyResponse = array(
                "token" => $userToken,
                "name" => $userInDatabase['name']
            );
            echo $response->successfully("Autenticacion realizada con exito", $bodyResponse);
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
                !isset($userData['nRut'])
            ) {
                http_response_code(400);
                echo $response->error400();
                die();
            }

            $email = $userData['email'];
            $name = $userData['name'];
            $surname = $userData['surname'];
            $phone = $userData['phone'];
            $password = $userData['password'];
            $address = $userData['address'];
            $typeCustomer = $userData['typeCustomer'];
            $company = $userData['company'];
            $nRut = $userData['nRut'];

            $formValid = true; //Esta bandera es para verificar que el formulario sea valido.

            foreach ($userData as $value) { //Valida que ningun dato venga nulo y que no sean string vacios.
                if (empty($value)) $formValid = false;
            }

            //Validamos que sea un email valido, ademas del nombre y apellido.
            if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match("/^[a-zA-z]*$/", $name) || !preg_match("/^[a-zA-z]*$/", $surname)) $formValid = false;
            
            //Validamos que el telefono sea un entero, la contraseña sea mayor a 5 digitos y que la direccion sea un string
            if (!is_int($phone) || !(strlen($password) > 6) || !is_string($address)) $formValid = false;

            //Si entro en algun if anterior, seignifica que tiene campos invalidos, por lo tanto:
            if (!$formValid) {
                http_response_code(400);
                echo $response->error400();
                die();
            }

            $newCustomer = new Customer($email, $name, $surname, $phone, $password, $address, $typeCustomer, $company, $nRut);
            $customerExist = Customer::getCustomerByEmail($email);
            if($customerExist){
                http_response_code(200);
                echo $response->error200("Ya existe un cliente ingresado con el email: " . $customerExist['email']);
                die();
            }

            $resultOfSave = $newCustomer->save();
            if ($resultOfSave) {
                http_response_code(200);
                echo $response->successfully("Cliente dado de alta con exito");
            } else {
                http_response_code(500);
                echo $response->error500();
            }  
            
    }

} else {
    //Metodo no permitido.
    echo $response->error405();
    die();
}
