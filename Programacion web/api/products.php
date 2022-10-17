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
    if(isset($_GET['idPromo'])){            //Todos los productos de una promo
        $idProduct = $_GET['idPromo'];
        $product->getProductsOfPromo($idProduct);
        die();
    }
    if(isset($_GET['idProduct'])){          //Todos lod modelos ACTIVOS de un producto
        $idProduct = $_GET['idProduct'];
        $product->getModelsOfProduct($idProduct);
        die();
    }if(isset($_GET['idPromoAll'])){            //Todos los productos de una promo
        $idProduct = $_GET['idPromoAll'];
        $product->getAllProductsOfPromo($idProduct);
        die();
    }
    if(isset($_GET['idProductAll'])){       //Todos los modelos de un producto, esten o no activos 
        $idProduct = $_GET['idProductAll'];
        $product->getAllModelsOfProduct($idProduct);
        die();
    }
    if(isset($_GET['barcode'])){            //Obtener producto por codigo de barra este o no ACTIVOS
        $barcode = $_GET['barcode'];        
        $product->getProductByBarcode($barcode);
        die();
    }
    if(isset($_GET['name'])){               //obtner productos por name 
        $name = $_GET['name'];
        $product->getProductByName($name);
        die();
    }
    if(isset($_GET['nameAll'])){               //obtner productos por name 
        $name = $_GET['nameAll'];
        $product->getAllProductByName($name);
        die();
    }
    if(isset($_GET['categoryName'])){       //Todos los productos ACTIVOS para el talle de nombre indicado
        $nameCategory = $_GET['categoryName'];
        $product->getProductByNameCategoy($nameCategory);
        die();
    }
    /*if(isset($_GET['sizeName'])){           //Todos los productos ACTIVOS para el talle de nombre indicado
        $Size = $_GET['sizeName'];
        $product->getProductByNameSize($nameSize);
        die();
    }
    if(isset($_GET['designName'])){         //Todos los productos ACTIVOS para el talle de nombre indicado
        $nameDesign = $_GET['designName'];
        $product->getProductByNameDesign($nameDesign);
        die();
    }*/
    
    if(isset($_GET['search'])){
        $search = $_GET['search'];
        switch ($search){    
                case 'disable':
                $product->getDisableProducts();
                die();
                case 'all':
                $product->getAllProducts();
                die();
                default :
                http_response_code(400);
                echo $response->error400("Accion no valida");
                die();
            }
        }
    $product->getActiveProducts();
    
        
}else if($_SERVER['REQUEST_METHOD'] === 'PATCH'){
    if(!isset($_GET['barcode']) || !isset($_GET['idProduct'])){
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
            $product->updateModel($barcode,$productData);
            die();        
            case 'disable':
            $product->disableModel($barcode);
            die();
            case 'active':
            $product->activeModel($barcode);
            die();
            default :
            http_response_code(400);
            echo $response->error400("Accion no valida");
            die();
        }
    }
    if(isset($_GET['idProduct']) && isset($_GET['action'])){
        $action = $_GET['action'];
        $idProduct = $_GET['idProduct'];
        switch ($action){
                case 'edit':
                    $product->updateLineOfProducts($idProduct,$productData);
                    die();
                /*case 'editPromo':
                    $product->updatePromo($idProduct,$productData);
                    die();
                case 'undoPromo':
                    $product->undoPromo($idProduct,$productData);
                    die();
                case 'addToPromo':
                    $product->addToPromo($idProduct,$productData);
                    die();            
                */case 'disable':
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