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
}else if($_SERVER['REQUEST_METHOD'] === 'PUT'){
    if(!isset($_GET['barcode'])){
        echo $response->error203("Error falta especificar codigo de barra");    
        die();
    }    
    $barcode = $_GET['barcode'];
    $product->updateProducts($barcode,$productData);
    die();

}else {
    echo $response->error203("Metodo no permitido");
}
?>