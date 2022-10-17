<?php
include('./controllers/DeliveryController.php');
header('Content-Type: application/json'); 
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

$response = new Response(); //Esta instancia va a ser utilizado a lo largo del controlador para las respuestas.
$delivery = new DeliveryController();
$bodyOfRequest = file_get_contents('php://input'); //Obtiene el body de la request sin procesar(JSON).
$deliveryData = json_decode($bodyOfRequest, 1); //Transforma el JSON en un array asosciativo.

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //Crear talle
    $delivery->saveDelivery($deliveryData);

}else if($_SERVER['REQUEST_METHOD'] === 'GET'){

    if(isset($_GET['nameDelivery'])){
        $nameDelivery = $_GET['nameDelivery'];
        $delivery->getDeliveryName($nameDelivery);
        die();
    }
    if(isset($_GET['idDelivery'])){
        $idDelivery = $_GET['idDelivery'];
        $delivery->getDeliveryId($idDelivery);
        die();
    }
    $delivery->getDeliverys();

}else if($_SERVER['REQUEST_METHOD'] === 'PATCH'){
    //Editar talle
    if(!isset($_GET['idDelivery'])){
        echo $response->error203("Error falta Id");    
        die();
    }    
    $idDelivery = $_GET['idDelivery'];
    $delivery->updateDelivery($idDelivery, $deliveryData);
    die();
    
}else if($_SERVER['REQUEST_METHOD'] === 'DELETE'){

    if(!isset($_GET['idDelivery'])){
        echo $response->error203("Error falta Id");    
        die();
    }
    $idDelivery = $_GET['idDelivery'];
    $delivery->deleteDelivery($idDelivery);
    die();
}else {
    echo $response->error203("Metodo no permitido");
}
?>