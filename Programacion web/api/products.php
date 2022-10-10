<?php
include('./controllers/ProductController.php');
header('Content-Type: application/json'); 
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

$response = new Response(); //Esta instancia va a ser utilizado a lo largo del controlador para las respuestas.
$product = new ProductController();
$bodyOfRequest = file_get_contents('php://input'); //Obtiene el body de la request sin procesar(JSON).
$productData = json_decode($bodyOfRequest, 1); //Transforma el JSON en un array asosciativo.

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //Crear talle
    $product->saveProduct($productData);

}else if($_SERVER['REQUEST_METHOD'] === 'GET'){
    
    if(isset($_GET['disable'])){
        $product->getDisableProducts();
        die();
    }
    if(isset($_GET['barcode'])){
        $barcode = $_GET['barcode'];
        $product->getProductByBarcode($barcode);
        die();
    }
    if(isset($_GET['idProduct'])){
        $idProduct = $_GET['idProduct'];
        $product->getProductById($idProduct);
        die();
    }
    $product->getProducts();
}else if($_SERVER['REQUEST_METHOD'] === 'PATCH'){
    if(!isset($_GET['barcode'])){
        echo $response->error203("Error falta especificar codigo de barra");    
        die();
    }
    if(!isset($_GET['action'])){
        echo $response->error203("Error falta especificar accion a realizar");    
        die();
    }
    $action = $_GET['action'];
    $barcode = $_GET['barcode'];
    switch ($action){
        case 'edit':
            $product->updateProducts($barcode,$productData);
            die();        
        case 'disable':
            $product->disableProduct($barcode);
            die();
        case 'active':
            $product->activeProduct($barcode);
            die();
        default :
        http_response_code(400);
        echo $response->error400("valor de accion invalido");
        die();
    }  
}else {
    echo $response->error203("Metodo no permitido");
}
?>