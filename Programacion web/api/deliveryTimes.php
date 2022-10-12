<?php
include('./controllers/DeliveryTimeController.php');
header('Content-Type: application/json'); 
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

$response = new Response(); //Esta instancia va a ser utilizado a lo largo del controlador para las respuestas.
$deliveryTime = new DeliveryTimeController();
$bodyOfRequest = file_get_contents('php://input'); //Obtiene el body de la request sin procesar(JSON).
$deliveryTimeData = json_decode($bodyOfRequest, 1); //Transforma el JSON en un array asosciativo.

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //Crear talle
    $deliveryTime->saveDeliveryTime($deliveryTimeData);

}else if($_SERVER['REQUEST_METHOD'] === 'GET'){

    if(isset($_GET['idDelivery'])){
        $idDeliveryTime = $_GET['idDelivery'];
        $deliveryTime->getDeliveryTimeId($idDeliveryTime);
        die();
    }
    $deliveryTime->getDeliveryTimes();

}else if($_SERVER['REQUEST_METHOD'] === 'PATCH'){
    if(!isset($_GET['idDelivery'])){
        echo $response->error203("Error - falta Id");    
        die();
    }    
    $idDeliveryTime = $_GET['idDelivery'];
    $deliveryTime->updateDeliveryTime($idDeliveryTime, $deliveryTimeData);
    die();
    
}else if($_SERVER['REQUEST_METHOD'] === 'DELETE'){

    if(!isset($_GET['idDeliveryTime'])){
        echo $response->error203("Error falta Id");    
        die();
    }
    $idDeliveryTime = $_GET['idDeliveryTime'];
    $deliveryTime->deleteDeliveryTime($idDeliveryTime);
    die();
}else {
    echo $response->error203("Metodo no permitido");
}
?>