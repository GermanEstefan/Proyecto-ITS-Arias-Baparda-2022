<?php

require_once('./helpers/Token.php');
require_once('./models/UserModel.php');
require_once('./models/EmployeeModel.php');
require_once('./helpers/Response.php');

header('Content-Type: application/json'); //Le decimos al agente que consuma el servidor que vamos a devolver JSON.
header('Access-Control-Allow-Origin: *'); //CORS
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

$jwt = new Token();
$response = new Response();

if($_SERVER['REQUEST_METHOD'] === 'GET'){

    $idOfUser = $jwt->verifyTokenAndGetIdUserFromRequest();
    $userData = UserModel::getUserById($idOfUser);
    $employeeData = EmployeeModel::getEmployeeById($idOfUser);

    if($userData['state'] == 0){
        http_response_code(401);
        echo $response->error401("Este usuario se encuentra dado de baja, fallo la autenticacion.");
        die();
    }

    if($employeeData){
        $bodyResponse = array(
            "email" => $userData['email'],
            "name" => $userData['name'],
            "surname" => $userData['surname'],
            "phone" => $userData['phone'],
            "address" => $userData['address'],
            "rol" => $employeeData['employee_role']
        );
        http_response_code(200);
        echo $response->successfully("Token valido", $bodyResponse);
        die();
    }else{
        $bodyResponse = array(
            "email" => $userData['email'],
            "name" => $userData['name'],
            "surname" => $userData['surname'],
            "phone" => $userData['phone'],
            "address" => $userData['address']
        );
        http_response_code(200);
        echo $response->successfully("Token valido", $bodyResponse);
        die();
    }

  
}
?>