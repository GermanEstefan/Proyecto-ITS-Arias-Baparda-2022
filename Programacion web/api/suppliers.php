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
    if(isset($_GET['active'])){
        $supplier->getAllSuppliersActive();
        die();
    }

    if(isset($_GET['disable'])){
        $supplier->getAllSuppliersDisable();
        die();
    }

    if(isset($_GET['all'])){
        $supplier->getAllSuppliers();
        die();
    }

    if(isset($_GET['rut'])){
        $rut = $_GET['rut'];
        $supplier->getSupplierByRut($rut);
        die();
    }

    if(isset($_GET['company'])){
        $company = $_GET['company'];
        $supplier->getSupplierByName($companyName);
        die();
    }

} else if ($_SERVER['REQUEST_METHOD'] === 'PATCH'){
    if (!isset($_GET['idSupplier'])){
        echo $response->error203("Error falta especificar el rut");
        die();
    }
    if (!isset($_GET['action'])){
        echo $response->error203("Error falto especificar accion a realizar");
        die();
    }
    $action = $_GET['action'];
    $idSupplier = $_GET['idSupplier'];
    switch ($action){
        case 'update':
            $supplier->updateSupplier($idSupplier,$supplierData);
            die();
        case 'disable':
            $supplier->disableSupplier($idSupplier);
            die();
        case 'enable':
            $supplier->disableSupplier($idSupplier);
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