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
    
    if(isset($_GET['promo'])){
        $product->savePromo($productData);
        die();
    }
    if(isset($_GET['product'])){
        $product->saveProduct($productData);
        die();
    }     
}else if($_SERVER['REQUEST_METHOD'] === 'GET'){
    
    if(isset($_GET['disable'])){
        $product->getDisableProducts();
        die();
    }
    if(isset($_GET['all'])){
        $product->getAllProducts();
        die();
    }
    if(isset($_GET['name'])){
        $name = $_GET['name'];
        $product->getProductsByName($name);
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
    if(isset($_GET['sizeName'])){
        $Size = $_GET['sizeName'];
        $product->getProductByNameSize($nameSize);
        die();
    }
    if(isset($_GET['designName'])){
        $nameDesign = $_GET['designName'];
        $product->getProductByNameDesign($nameDesign);
        die();
    }
    if(isset($_GET['categoryName'])){
        $nameCategory = $_GET['categoryName'];
        $product->getProductsByNameCategoy($nameCategory);
        die();
    }
    $product->getProducts();
}else if($_SERVER['REQUEST_METHOD'] === 'PATCH'){
    if(!isset($_GET['barcode']) && !isset($_GET['idProduct'])){
        echo $response->error203("Error falta especificar tipo de atributo");    
        die();
    }
    if(!isset($_GET['action'])){
    echo $response->error203("Error falta especificar accion a realizar");    
    die();
    }
    if(isset($_GET['barcode']) && isset($_GET['action'])){
    $action = $_GET['action'];
    $barcode = $_GET['barcode'];
    switch ($action){
        case 'edit':
            $product->updateProduct($barcode,$productData);
            die();        
            case 'disable':
            $product->disableProduct($barcode);
            die();
            case 'active':
            $product->activeProduct($barcode);
            die();
            default :
            http_response_code(400);
            echo $response->error400("Accion no valida");
            die();
        }
    }if(isset($_GET['idProduct']) && isset($_GET['action'])){
        $action = $_GET['action'];
        $idProduct = $_GET['idProduct'];
        switch ($action){
            case 'edit':
                $product->updateLineOfProducts($idProduct,$productData);
                die();        
                case 'disable':
                $product->disableLineOfProduct($idProduct);
                die();
                case 'active':
                $product->activeLineOfProduct($idProduct);
                die();
                default :
                http_response_code(400);
                echo $response->error400("Accion no valida");
                die();
            }
        }
}else{
    echo $response->error203("Metodo no permitido");
}
?>