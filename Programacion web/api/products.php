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
$promoData =  json_decode($bodyOfRequest, 1);
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(!isset($_GET['type'])){
        echo $response->error203("Error debe indicar un Type");    
        die();
        }
        $type = $_GET['type'];
        switch ($type){    
                case 'promo':
                $product->savePromo($promoData);
                die();
                case 'product':
                $product->saveProduct($productData);
                die();
                default :
                http_response_code(400);
                echo $response->error400("Accion no valida");
                die();
        }
    
}else if($_SERVER['REQUEST_METHOD'] === 'GET'){
    
    //CLIENTE
    //1-obtener productos ACTIVOS por name
    if(isset($_GET['name'])){ 
        $name = $_GET['name'];
        $product->getProductByName($name);
        die();
    }
    //2-todos los productos ACTIVOS para una categoria
    if(isset($_GET['categoryName'])){
        $nameCategory = $_GET['categoryName'];
        $product->getProductByNameCategoy($nameCategory);
        die();
    }
    //3-todos los modelos ACTIVOS para un producto
    if(isset($_GET['modelsOfProduct'])){
        $idProduct = $_GET['modelsOfProduct'];
        $product->getModelsOfProduct($idProduct);
        die();
    }
    //4-todos los productos ACTIVOS
    if(isset($_GET['products'])){
        $product->getActiveProducts();
        die();
    }
    //5-todos los productos de una promo ACITVA
    if(isset($_GET['productsOfPromo'])){
        $idProduct = $_GET['productsOfPromo'];
        $product->getProductsOfPromo($idProduct);
        die();
    }
    //6-Conusltar producto por barcode
    if(isset($_GET['barcode'])){
        $barcode = $_GET['barcode'];        
        $product->getProductByBarcode($barcode);
        die();
    }
    //7 Obtener todas las promos activas
    if(isset($_GET['promos'])){
        $product->getActivePromos();
        die();
    }


    //BACKOFFICE
    //A-obtener CUALQUIER producto por name (incluye promos)
    if(isset($_GET['BOname'])){ 
        $name = $_GET['BOname'];
        $product->getProductByNameBO($name);
        die();
    }
    //B- Obtener CUALQUIER producto por barcode (incluye promos)
    if(isset($_GET['BObarcode'])){
        $barcode = $_GET['BObarcode'];        
        $product->getProductByBarcodeBO($barcode);
        die();
    }
    //C- Obtener TODOS los modelos para un producto
    if(isset($_GET['BOmodelsOfProduct'])){  
        $idProduct = $_GET['BOmodelsOfProduct'];
        $product->getModelsOfProductBO($idProduct);
        die();
    }
    //D- Obtener todas las promos
    if(isset($_GET['BOPromos'])){   
        $product->getPromosBO();
        die();
    }
    //E - Obtener todos los productos
    if(isset($_GET['BOProducts'])){   
        $product->getProductsBO();
        die();
    }
    //G- Obtener TODOS los productos para un promo
    if(isset($_GET['BOproductsOfPromo'])){  
        $idProduct = $_GET['BOproductsOfPromo'];
        $product->getProductsOfProductBO($idProduct);
        die();
    }
    if(isset($_GET['BOfilter'])){
        $filter = $_GET['BOfilter'];
        switch ($filter){
                case 'productsDisable':
                    $product->productsDisableBO();
                    die();
                case 'promosDisable':
                    $product->promosDisableBO();
                    die();            
                default :
                    http_response_code(400);
                    echo $response->error400("Filtro aplicado no valido");
                    die();
            }
    }


echo $response->error203("Accion no valida"); 
        
}else if($_SERVER['REQUEST_METHOD'] === 'PATCH'){

    if(isset($_GET['barcode']) && isset($_GET['actionMin'])){
    $actionMin = $_GET['actionMin'];
    $barcode = $_GET['barcode'];
    switch ($actionMin){
        case 'edit':
            $product->updateModel($barcode,$productData);
            die();        
            case 'disable':
            $product->disableModel($barcode);
            die();
            case 'active':
            $product->activeModel($barcode);
            die();
            default:
            http_response_code(400);
            echo $response->error400("Accion no valida");
            die();
        }
    }
    if(isset($_GET['idProduct']) && isset($_GET['actionMax'])){
        $actionMax = $_GET['actionMax'];
        $idProduct = $_GET['idProduct'];
        switch ($actionMax){
                case 'edit':
                    $product->updateLineOfProducts($idProduct,$productData);
                    die();
                case 'editPromo':
                    $product->updatePromo($idProduct,$promoData);
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