<?php

include_once('./helpers/Response.php');
include_once("./helpers/Token.php");
include_once("./models/ProductModel.php");
include_once("./models/CategoryModel.php");
include_once("./models/DesignModel.php");
include_once("./models/SizeModel.php");

class ProductController {

    private $response;
    private $jwt;
    
    function __construct()
    {
        $this->response = new Response();
        $this->jwt = new Token();
    }

    private function validateBodyOfProduct($productData){
        if( !isset($productData['idProduct']) 
        ||  !isset($productData['name']) 
        ||  !isset($productData['prodCategory']) 
        ||  !isset($productData['prodDesign']) 
        ||  !isset($productData['prodSize']) 
        ||  !isset($productData['stock']) 
        ||  !isset($productData['price']) 
        ||  !isset($productData['description'])) return false;
    return $productData;
    }
    //ALTA
    public function saveProduct($productData){
        /*
            En este metodo no precisamos el ID del usuario, lo unico que validamos es que tenga un token y sea valido. 
            Si no tiene token, no pasa de la funcion para abajo por que el metodo mismo le niega el acceso.
        */
        $this->jwt->verifyTokenAndGetIdUserFromRequest(); 
        $bodyIsValid = $this->validateBodyOfProduct($productData);
        if(!$bodyIsValid){
             echo $this->response->error400('Error en los datos enviados');
        die();
        }
        $idProduct = $productData['idProduct'];
        $name = $productData['name'];
        $prodCategory = $productData['prodCategory'];
        $prodDesign = $productData['prodDesign'];
        $prodSize = $productData['prodSize'];
        $stock = $productData['stock'];
        $price = $productData['price'];
        $description = $productData['description'];
        
        //Valido que exista la categoria
        $categoryExist = CategoryModel::getCategoryById($prodCategory);
        if(!$categoryExist){
            echo $this->response->error203("Esta intentando ingresar una categoria que no existe");
            die();
        }
        
        //Valido que exista el talle
        $sizeExist = SizeModel::getSizeById($prodSize);
        if(!$sizeExist){
            echo $this->response->error203("Esta intentando ingresar una talle que no existe");
            die();
        }
        
        //Valido que exista el diseño
        $designExist = DesignModel::getDesignById($prodDesign);
        if(!$designExist){
            echo $this->response->error203("Esta intentando ingresar un diseño que no existe");
            die();
        }
        
        //Valido que no exista el producto
        $productExist = ProductModel::identifyProduct($idProduct,$prodCategory,$prodDesign,$prodSize);
        if($productExist){
            echo $this->response->error203("Esta intentando ingresar un producto ya existente");
            die();
        }
        
        $product = new ProductModel($idProduct,$name,$prodCategory,$prodDesign,$prodSize,$stock,$price,$description);
        $result = $product->save();
        if(!$result){
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Producto creado con exito");
    }
    //CONSULTAS
    public function getProducts(){
        $products = ProductModel::getAllProducts();
        if(!$products){
            echo $this->response->error203("No hay Productos");
            die();
        }
        echo json_encode($products); 
    }
    public function getProductById($idProduct){
        $product = ProductModel::getProductById($idProduct);
        if(!$product){
            echo $this->response->error203("No existe producto con ID = $idProduct");
            die();
        }
        echo json_encode($product);  
    }
    public function getProductByBarcode($barcode){
        $product = ProductModel::getProductByBarcode($barcode);
        if(!$product){
            echo $this->response->error203("No existe el producto con el codigo de barras $barcode");
            die();
        }
        echo json_encode($product);  
    }
}