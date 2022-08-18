<?php
include('./helpers/Response.php');
include("./models/User.php");
include("./models/Employee.php");
include("./helpers/Token.php");

$response = new Response(); //Esta instancia va a ser utilizado a lo largo del controlador para las respuestas.
$jwt = new Token();
header('Content-Type: application/json'); //Le decimos al agente que consuma el servidor que vamos a devolver JSON.

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $bodyOfRequest = file_get_contents('php://input'); //Obtiene el body de la request sin procesar(JSON).
    $userData = json_decode($bodyOfRequest, 1); //Transforma el JSON en un array asosciativo.

    if (!isset($_GET['url'])) {
        echo $response->error400();
        die();
    }

    $url = $_GET['url']; //Este valor sirve para diferenciar la accion(login, registro, etc). 
    switch ($url) {
        case 'login':

            if (!isset($userData['ci']) || !isset($userData['password'])) {
                http_response_code(400);
                echo $response->error400();
                die();
            }

            $ci = $userData['ci'];
            $password = $userData['password'];

            $employeeExistInDatabase = Employee::getEmployeeByCi($ci);
            if (!$employeeExistInDatabase) {
                http_response_code(200);
                echo $response->error200("El empleado con la ci: $ci no existe");
                die();
            }
            $employeeId = $employeeExistInDatabase['id_employe'];
            $userInDatabase = User::getUserById($employeeId);
            $userInDatabasePassword = $userInDatabase['password'];
            if (!($password == $userInDatabasePassword)) {
                http_response_code(401);
                echo $response->error401('Credenciales incorrectas');
                die();
            }
            /*Aca hay que generar un token con los datos del usuario y mandarselos al cliente, para que luego, en cada request se mande ese token para validar*/
            $userToken = $jwt->generateToken($employeeId);
            $bodyResponse = array(
                "token" => $userToken,
                "name" => $userInDatabase['name']
            );
            echo $response->successfully("Autenticacion realizada con exito", $bodyResponse);
            die();

        case 'register':
            $formValid = true; //Esta bandera es para verificar que el formulario sea valido.
            if (
                !isset($userData['email']) ||
                !isset($userData['name']) ||
                !isset($userData['surname']) ||
                !isset($userData['phone']) ||
                !isset($userData['password']) ||
                !isset($userData['address']) ||
                !isset($userData['rol']) ||
                !isset($userData['ci'])
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
            $rol = $userData['rol'];
            $ci = $userData['ci'];

            foreach ($userData as $value) { //Valida string vacios.
                if (empty($value)) $formValid = false;
            }

            //Validamos que sea un email valido, ademas del nombre.
            if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match("/^[a-zA-z]*$/", $name) ) $formValid = false;

            //Validamos la contraseña sea mayor a 5 digitos, que la direccion sea un string y sea un rol valido.
            $validRols = array('VENDEDOR', 'COMPRADOR', 'JEFE');
            if ( !(strlen($password) > 6) || !is_string($address) || !in_array(strtoupper($rol), $validRols)) $formValid = false;

            //Si entro en algun if anterior, seignifica que tiene campos invalidos, por lo tanto:
            if (!$formValid) {
                http_response_code(400);
                echo $response->error400();
                die();
            }

            //Verificacion del token.
            if (!isset(getallheaders()['access-token'])) {
                http_response_code(401);
                echo $response->error401("No hay un token presente");
                die();
            }

            //Si tiene un token, y es valido lo decodificamos y obtenemos su ID.
            $idOfUser = $jwt->verifyToken(getallheaders()['access-token'])->data->idUser;
            $employee = Employee::getEmployeeById($idOfUser);
            $rolOfEmployee = $employee['name_rol'];
            if(!($rolOfEmployee == 'JEFE')){
                http_response_code(401);
                echo $response->error401("Usted no esta autorizado para relizar esta accion");
                die();
            }
    
            $newEmployee = new Employee($email, $name, $surname, $phone, $password, $address, $rol, $ci);
            $employeeExist = Employee::getEmployeeByCi($ci);

            if ($employeeExist) {
                http_response_code(200);
                echo $response->error200("Ya existe un empleado ingresado con la CI: " . $employeeExist['ci']);
                die();
            }
            $resultOfSave = $newEmployee->save();
            if ($resultOfSave) {
                http_response_code(200);
                echo $response->successfully("Empleado dado de alta con exito");
            } else {
                http_response_code(500);
                echo $response->error500();
            }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {

    if (!isset(getallheaders()['access-token'])) {
        http_response_code(401);
        echo $response->error401("No hay un token presente");
        die();
    }
    $jwt->verifyToken($tokenOfUser);
    echo $response->successfully("Autenticacion realizada con exito");

} else {
    //Metodo no permitido.
    echo $response->error405();
    die();
}
