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
    //OBTENER VENTA POR ID
    if(isset($_GET['idSale'])){
        $idSale = $_GET['idSale'];
        $sale->getSaleId($idSale);
        die();
    }
    //OBTENER EL DETALLE DE LAS VENTAS
    if(isset($_GET['saleDetail'])){
        $idSale = $_GET['saleDetail'];
        $sale->getDetailForSale($idSale);
        die();
    }
    //OBTENER VENTAAS QUE SE ENCUENTREN EN DETERMINADO ESTADO 
    if(isset($_GET['status'])){
        $status = $_GET['status'];
        $sale->getSaleByStatus($status);
        die();
    }
    //OBTENER LA DIRECCION DEL CLIENTE PARA SUGERIRLA (NACHO)
    if(isset($_GET['suggestAddress'])){
        $email = $_GET['suggestAddress'];
        $sale->getAddresToCustomer($email);
        die();
    }
    //OBTENER EL HISTORIAL DE REPORTES PARA UNA VENTA
    if(isset($_GET['reportHistory'])){
        $idSale = $_GET['reportHistory'];
        $sale->getReportHistory($idSale);
        die();
    }
    //OBTENER VENTAS PARA UNA FECHA "DATE%" 
    if(isset($_GET['salesForDay'])){
        $day = $_GET['salesForDay'];
        $sale->getAllSalesForDay($day);
        die();
    }
    /*
    if(isset($_GET['from'])& isset($_GET['until'])){
        $fromDay = $_GET['from'];
        $untilDay = $_GET['until'];
        $sale->getSalesForRange($fromDay,$untilDay);
        die();
    }
    */
    //OBTENER TODAS LAS VENTAS PARA UN CLIENTE (NACHO)
    if(isset($_GET['salesClient'])){
        $email = $_GET['salesClient'];
        $sale->getSalesForUser($email);
        die();
    }
    //OBTENER EL HISTORIAL DE REPORTES PARA UNA VENTA (NACHO)
    if(isset($_GET['History'])){
        $idSale = $_GET['History'];
        $sale->getReportHistoryByClient($idSale);
        die();
    }
    //OBTENER TODAS LAS VENTAS QUE TENGASN DETERMINADO RANGO HORARIO 
    if(isset($_GET['deliveryTime'])){
        $idDelivery = $_GET['deliveryTime'];
        $sale->getSalesByDelivery($idDelivery);
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