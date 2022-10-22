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

    if(isset($_GET['nameSale'])){
        $nameSale = $_GET['nameSale'];
        $sale->getSaleByName($nameSale);
        die();
    }
    if(isset($_GET['idSale'])){
        $idSale = $_GET['idSale'];
        $sale->getSaleById($idSale);
        die();
    }
    $sale->getAllSale();

}else if($_SERVER['REQUEST_METHOD'] === 'PATCH'){
    //Editar categoria
    if(isset($_GET['idSale'])){
        $idSale = $_GET['idSale'];
        $sale->updateSale($idSale, $saleData);    
        die();
    }
    
}else {
    echo $response->error200("Metodo no permitido");
}
?>