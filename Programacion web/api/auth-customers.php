<?php
include('./helpers/Response.php');
include('./controllers/CustomerController.php');

header('Content-Type: application/json'); //Le decimos al agente que consuma el servidor que vamos a devolver JSON.
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

$response = new Response();
if (!isset($_GET['url'])) {
    echo $response->error400();
    die();
}
$url = $_GET['url']; //Este valor sirve para diferenciar la accion(login, registro, etc). 
$bodyOfRequest = file_get_contents('php://input'); //Obtiene el body de la request sin procesar(JSON).
$userData = json_decode($bodyOfRequest, 1); //Transforma el JSON en un array asosciativo.

$customerController = new CustomerController();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    switch ($url) {
        case 'login':
            $customerController->loginCustomer($userData);
            die();
        case 'register':
            $customerController->registerCustomer($userData);
            die();
        default:
            http_response_code(400);
            echo $response->error400("Parametro URL invalido");
            die();
    }
} else if ($_SERVER['REQUEST_METHOD'] == 'PUT') {

    switch ($url) {
        case 'update':
            $customerController->updateCustomer($userData);
            die();
        default:
            http_response_code(400);
            echo $response->error400("Parametro URL invalido");
            die();
    }

} else {
    //Metodo no permitido.
    echo $response->error405();
    die();
}
?>