<?php
include('./controllers/SupplierController.php');
header('Content-Type: application/json'); 
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

$response = new Response(); //Esta instancia va a ser utilizado a lo largo del controlador para las respuestas.
$supplier = new SupplierController();
$bodyOfRequest = file_get_contents('php://input'); //Obtiene el body de la request sin procesar(JSON).
$supplierData = json_decode($bodyOfRequest, 1); //Transforma el JSON en un array asosciativo.

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    //Crear proveedor
    $supplier->saveSupplier($supplierData);
} else if ($_SERVER['REQUEST_METHOD'] === 'GET'){
    //Sacar todos los proveedores activos
    if(isset($_GET['enable'])){
        $supplier->getActiveSuppliers();
        die();
    }
    //sacar todos los proveedores dados de baja
    if(isset($_GET['disable'])){
        $supplier->getDisabledSuppliers();
        die();
    }
    //sacar todos los proveedores
    if(isset($_GET['all'])){
        $supplier->getAllSuplliers();
        die();
    }
    //sacar provedoor por RUT
    if(isset($_GET['rut'])){
        $rut = $_GET['rut'];
        $supplier->getSupplierByRut($rut);
        die();
    }
    //sacar proveedor por nombre
    if(isset($_GET['company'])){
        $company = $_GET['company'];
        $supplier->getSupplierByName($company);
        die();
    }
    //Actualizar datos, dar de baja, dar de alta proveedor
} else if ($_SERVER['REQUEST_METHOD'] === 'PATCH'){
    if (!isset($_GET['rut'])){
        echo $response->error203("Error falta especificar el rut del proveedor");
        die();
    }
    if (!isset($_GET['action'])){
        echo $response->error203("Error falto especificar accion a realizar (Actualizar, dar de baja, dar de alta)");
        die();
    }
    $action = $_GET['action'];
    $rut = $_GET['rut'];
    switch ($action){
        case 'update':
            $supplier->updateSupplier($supplierData);
            die();
        case 'disable':
            $supplier->disableSupplier($supplierData);
            die();
        case 'enable':
            $supplier->enableSupplier($supplierData);
            die();
        default:
            http_response_code(400);
            echo $response->error400("Accion no valida");
            die();
    }
} else {
    echo $response->error203("Metodo no permitido");
}
?>