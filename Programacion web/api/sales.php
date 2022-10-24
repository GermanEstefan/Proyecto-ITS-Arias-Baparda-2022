<?php
include('./controllers/SaleController.php');
header('Content-Type: application/json'); 
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

$response = new Response(); //Esta instancia va a ser utilizado a lo largo del controlador para las respuestas.
$sale = new SaleController();
$bodyOfRequest = file_get_contents('php://input'); //Obtiene el body de la request sin procesar(JSON).
$saleData = json_decode($bodyOfRequest, 1); //Transforma el JSON en un array asosciativo.

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //Crear estado
    $sale->saveSale($saleData);

}else if($_SERVER['REQUEST_METHOD'] === 'GET'){

    if(isset($_GET['idSale'])){
        $idSale = $_GET['idSale'];
        $sale->getSaleId($idSale);
        die();
    }
    if(isset($_GET['saleDetail'])){
        $idSale = $_GET['saleDetail'];
        $sale->getDetailForSale($idSale);
        die();
    }
    if(isset($_GET['status'])){
        $status = $_GET['status'];
        $sale->getSaleByStatus($status);
        die();
    }
    if(isset($_GET['suggestAddress'])){
        $email = $_GET['suggestAddress'];
        $sale->getAddresToCustomer($email);
        die();
    }
    
    
}else if($_SERVER['REQUEST_METHOD'] === 'PATCH'){
    //Editar categoria
    if(isset($_GET['actualizeSale'])){
        $idSale = $_GET['actualizeSale'];
        $sale->updateReport($idSale,$saleData);
        die();
    }
    
}else {
    echo $response->error200("Metodo no permitido");
}
?>