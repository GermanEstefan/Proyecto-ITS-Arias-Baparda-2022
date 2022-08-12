<?php
include('./helpers/Response.php');
include("./models/User.php");
include("./models/Employee.php");
include("./models/Customer.php");

$response = new Response(); //Esta instancia va a ser utilizado a lo largo del controlador para las respuestas.
header('Content-Type: application/json'); //Le decimos al agente que consuma el servidor que vamos a devolver JSON.

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $bodyOfRequest = file_get_contents('php://input'); //Obtiene el body de la request sin procesar(JSON).
    $userData = json_decode($bodyOfRequest, 1); //Transforma el JSON en un array asosciativo.
    if( 
        !isset($userData['mail']) || 
        !isset($userData['name']) || 
        !isset($userData['surname']) || 
        !isset($userData['phone']) || 
        !isset($userData['password']) ||
        !isset($userData['address']) ||
        !isset($_GET['typeUser'])
    ) {
        http_response_code(400);
        echo $response->error400();
        die();
    }

    $typeOfUser = $_GET['typeUser']; //Este valor identifica cuando es un usuario CLIENTE(1) o FUNCIONARIO(2)
    $mail = $userData['mail'];
    $name = $userData['name'];
    $surname = $userData['surname'];
    $phone = $userData['phone'];
    $password = $userData['password'];
    $address = $userData['address'];

    $formValid = true; //Esta bandera es para verificar que el formulario sea valido.
    
    foreach ($userData as $value) { //Valida que ningun dato venga nulo y que no sean string vacios.
        if (empty($value)) {
            $formValid = false;
        }
    }

    //Validamos que sea un email valido, ademas del nombre y apellido.
    if (!filter_var($mail, FILTER_VALIDATE_EMAIL) || !preg_match("/^[a-zA-z]*$/", $name) || !preg_match("/^[a-zA-z]*$/", $surname) ) {
        $formValid = false;
    }

    //Validamos que el telefono sea un entero, la contraseña sea mayor a 5 digitos y que la direccion sea un string
    if (!is_int($phone) || !(strlen($password) > 6) || !is_string($address)) {
        $formValid = false;
    }

    //Si entro en algun if anterior, seignifica que tiene campos invalidos, por lo tanto:
    if(!$formValid){
        http_response_code(400);
        echo $response->error400();
        die();
    }

    if ($typeOfUser == 1) {

        //Validar estos campos
        $typeCustomer = $userData->typeCustomer;
        $company = $userData->company;
        $nRut = $userData->nRut;

        //Aca hay que definir la logica para el usuario CLIENTE (PENDIENTE)

    } elseif ($typeOfUser == 2) {
        /*
            A TENER EN CUENTA: Aca hay que de algun forma validar que sea un usuario con rol de tipo JEFE.
            Lo mas seguro que se implemente un token de seguridad.
        */
        $ci = $userData['ci'];
        $rol = $userData['rol'];

        //Validamos que sea un rol valido.(1: VENDEDOR, 2: COMPRADOR, 3:JEFE)
        //Y que la CI sea un numerico
        $validRols = array(1,2,3);
        if(!in_array($rol, $validRols) || !is_int($ci)){
            http_response_code(400);
            echo $response->error400("Rol invalido");
            die();
        }
        $newEmployee = new Employee($mail, $name, $surname, $phone, $password, $address, $rol, $ci);
        $employeeExist = $newEmployee->getEmployee($ci);
        
        if($employeeExist->num_rows > 0){ //Verificamos que no exista un empleado con esa CI.
            http_response_code(200);
            echo $response->error200("El empleado con la ci: $ci ya existe");
        }else{
            $resultOfSave = $newEmployee->save();
            if($resultOfSave){
                http_response_code(200);
                echo $response->successfully("Empleado dado de alta con exito");
            }else{
                http_response_code(500);
                echo $response->error500();
            }
        }
        
    } else {
        http_response_code(400);
        echo $response->error400();
        die();
    }
}
?>
