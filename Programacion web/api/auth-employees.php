<?php

include('./helpers/Response.php');
include('./controllers/EmployeeController.php');

header('Content-Type: application/json'); //Le decimos al agente que consuma el servidor que vamos a devolver JSON.
header('Access-Control-Allow-Origin: *'); //CORS
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

$response = new Response(); //Esta instancia va a ser utilizado a lo largo del controlador para las respuestas.
$employeeController = new EmployeeController(); 
$bodyOfRequest = file_get_contents('php://input'); //Obtiene el body de la request sin procesar(JSON).
$userData = json_decode($bodyOfRequest, 1); //Transforma el JSON en un array asosciativo.

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    
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

} else if($_SERVER['REQUEST_METHOD'] === 'GET'){
    
    if(isset($_GET['idEmployee'])){
        $idEmployee = $_GET['idEmployee'];
        $employeeController->getInfoByidEmployee($idEmployee);
        die();
    }
    
    $employeeController->getEmployees();
    die();

} else if($_SERVER['REQUEST_METHOD'] === 'PATCH'){
    
    
    if(isset($_GET['idEmployee']) && isset($_GET['action'])){
        $action = $_GET['action'];
        $idEmployee = $_GET['idEmployee'];
        switch ($action){
            case 'edit':
                $employeeController->updateEmployee($idEmployee,$userData);
                die();        
                case 'disable':
                $employeeController->disableEmployee($idEmployee);
                die();
                case 'active':
                $employeeController->activeEmployee($idEmployee);
                die();
                default:
                http_response_code(400);
                echo $response->error400("Accion no valida");
                die();
            }
        }


}else{
    //Metodo no permitido.
    echo $response->error405();
    die();
}
