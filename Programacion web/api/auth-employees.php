<?php

include('./helpers/Response.php');
include("./helpers/Token.php");
include('./controllers/EmployeeController.php');

header('Content-Type: application/json'); //Le decimos al agente que consuma el servidor que vamos a devolver JSON.
header('Access-Control-Allow-Origin: *'); //CORS

$response = new Response(); //Esta instancia va a ser utilizado a lo largo del controlador para las respuestas.
$jwt = new Token();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $bodyOfRequest = file_get_contents('php://input'); //Obtiene el body de la request sin procesar(JSON).
    $userData = json_decode($bodyOfRequest, 1); //Transforma el JSON en un array asosciativo.
    $employeeController = new EmployeeController(); 

    if (!isset($_GET['url'])) {
        echo $response->error400();
        die();
    }
    $url = $_GET['url']; //Este valor sirve para diferenciar la accion(login, registro, etc). 

    switch ($url) {
        case 'login':
            $employeeController->loginEmployee($userData);
            die();
        case 'register':
            $employeeController->registerEmployee($userData);
            die();
        default :
            http_response_code(400);
            echo $response->error400("Parametro URL invalido");
            die();     
    }

} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {

    if (!isset(getallheaders()['access-token'])) {
        http_response_code(401);
        echo $response->error401("No hay un token presente");
        die();
    }
    $jwt->verifyToken(getallheaders()['access-token']);
    echo $response->successfully("Autenticacion realizada con exito");

} else {
    //Metodo no permitido.
    echo $response->error405();
    die();
}
