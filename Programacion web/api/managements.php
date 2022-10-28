<?php
include('./controllers/SupplyController.php');
header('Content-Type: application/json'); 
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

$response = new Response(); //Esta instancia va a ser utilizado a lo largo del controlador para las respuestas.
$supply = new SupplyController();
$bodyOfRequest = file_get_contents('php://input'); //Obtiene el body de la request sin procesar(JSON).
$supplyData = json_decode($bodyOfRequest, 1); //Transforma el JSON en un array asosciativo.

if($_SERVER['REQUEST_METHOD'] === 'GET'){
    //OBTENER COMPRA POR ID
    if(isset($_GET['balance'])){
        $supply->getBalances();
        die();
    }
    //OBTENER EL DETALLE DE LAS VENTAS
    if(isset($_GET['supplyDetail'])){
        $idSupply = $_GET['supplyDetail'];
        $supply->getDetailForSupply($idSupply);
        die();
    }
    //OBTENER VENTAS PARA UNA FECHA "DATE%" 
    if(isset($_GET['supplysForDay'])){
        $day = $_GET['supplysForDay'];
        $supply->getAllSupplysForDay($day);
        die();
    }   
}else {
    echo $response->error200("Metodo no permitido");
}
?>