<?php
include_once('./controllers/SupplyController.php');
header('Content-Type: application/json'); 
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

$response = new Response(); //Esta instancia va a ser utilizado a lo largo del controlador para las respuestas.
$supply = new SupplyController();
$bodyOfRequest = file_get_contents('php://input'); //Obtiene el body de la request sin procesar(JSON).
$supplyData = json_decode($bodyOfRequest, 1); //Transforma el JSON en un array asosciativo.

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    //crear registro de compra
    $supply->saveSupply($supplyData);
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if(isset($_GET['all'])){
        $supply->getAllSupply();
        die();
    }
    if(isset($_GET['allWithDetail'])){
        $supply->getAllSupplyWithDetail();
        die();
    }
    if(isset($_GET['byDetailId'])){
        $id = $_GET['byDetailId'];
        $supply->getDetailById($id);
        die();
    }
    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $supply->getSupplyById($id);
        die();
    }
    if(isset($_GET['employeeId'])){
        $id = $_GET['employeeId'];
        $supply->getSupplyMadeByEmployee($id);
        die();
    }
    if(isset($_GET['productId'])){
        $id = $_GET['productId'];
        $supply->getSupplyByProductId($id);
        die();
    }
    if(isset($_GET['supplierId'])){
        $id = $_GET['supplierId'];
        $supply->getSupplyBySupplierId($id);
        die();
    }
}