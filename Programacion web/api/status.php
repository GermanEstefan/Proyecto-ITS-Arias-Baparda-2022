<?php
include('./controllers/StatusController.php');
header('Content-Type: application/json'); 
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

$response = new Response(); //Esta instancia va a ser utilizado a lo largo del controlador para las respuestas.
$status = new StatusController();
$bodyOfRequest = file_get_contents('php://input'); //Obtiene el body de la request sin procesar(JSON).
$statusData = json_decode($bodyOfRequest, 1); //Transforma el JSON en un array asosciativo.

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //Crear estado
    $status->saveStatus($statusData);

}else if($_SERVER['REQUEST_METHOD'] === 'GET'){

    if(isset($_GET['nameStatus'])){
        $nameStatus = $_GET['nameStatus'];
        $status->getStatusByName($nameStatus);
        die();
    }
    if(isset($_GET['idStatus'])){
        $idStatus = $_GET['idStatus'];
        $status->getStatusById($idStatus);
        die();
    }
    $status->getAllStatus();

}else if($_SERVER['REQUEST_METHOD'] === 'PATCH'){
    //Editar categoria
    if(isset($_GET['idStatus'])){
        $idStatus = $_GET['idStatus'];
        $status->updateStatus($idStatus, $statusData);    
        die();
    }
    
}else {
    echo $response->error200("Metodo no permitido");
}
?>