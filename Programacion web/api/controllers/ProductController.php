<?php

include_once('./helpers/Response.php');
include_once("./helpers/Token.php");
include_once("./models/ProductModel.php");
include_once("./models/CategoryModel.php");
include_once("./models/DesignModel.php");
include_once("./models/SizeModel.php");

class ProductController
{
    private $response;
    private $jwt;

    function __construct()
    {
        $this->response = new Response();
        $this->jwt = new Token();
    }

    private function validateBodyOfProduct($productData)
    {
        if (
            !isset($productData['idProduct'])
            ||  !isset($productData['name'])
            ||  !isset($productData['prodCategory'])
            ||  !isset($productData['price'])
            ||  !isset($productData['description'])
            ||  !isset($productData['models'])
        ) return false;

        return $productData;
    }
    private function validateBodyOfAtribute($productData)
    {
        if (
            !isset($productData['name'])
            ||  !isset($productData['prodCategory'])
            ||  !isset($productData['price'])
            ||  !isset($productData['description'])
        ) return false;
        return $productData;
    }
    private function validateBodyOfPromo($productData)
    {
        if (
            !isset($productData['idProduct'])
            ||  !isset($productData['name'])
            ||  !isset($productData['price'])
            ||  !isset($productData['stock'])
            ||  !isset($productData['description'])
            ||  !isset($productData['contains'])
        ) return false;

        return $productData;
    }
    //ALTA
    public function saveProduct($productData)
    {
        /*
            En este metodo no precisamos el ID del usuario, lo unico que validamos es que tenga un token y sea valido. 
            Si no tiene token, no pasa de la funcion para abajo por que el metodo mismo le niega el acceso.
        */
        $this->jwt->verifyTokenAndGetIdUserFromRequest();
        $bodyIsValid = $this->validateBodyOfProduct($productData);
        if (!$bodyIsValid) {
            echo $this->response->error400('Error en los datos enviados');
            die();
        }

        $idProduct = $productData['idProduct'];
        $name = $productData['name'];
        $prodCategory = $productData['prodCategory'];
        $price = $productData['price'];
        $description = $productData['description'];
        $models = $productData['models'];

        $queries = array();
        $index = 0;

        foreach ($models as $model) {
            $prodDesign = $model['prodDesign'];
            $prodSize = $model['prodSize'];
            $stock = $model['stock'];

            //Valido que exista la categoria
            $categoryExist = CategoryModel::getCategoryById($prodCategory);
            if (!$categoryExist) {
                echo $this->response->error203("La categoria con el ID: $prodCategory no existe");
                die();
            }
            //Valido que exista el diseño
            $designExist = DesignModel::getDesignById($prodDesign);
            if (!$designExist) {
                echo $this->response->error203("El diseño con el ID: $prodDesign no existe");
                die();
            }

            //Valido que exista el talle
            $sizeExist = SizeModel::getSizeById($prodSize);
            if (!$sizeExist) {
                echo $this->response->error203("El talle con el ID: $prodSize no existe");
                die();
            }

            //Valido que no exista el producto
            $productExist = ProductModel::identifyProduct($idProduct, $prodDesign, $prodSize);
            if ($productExist) {
                echo $this->response->error203("Esta intentando ingresar un producto ya existente");
                die();
            }
            $query = array($index => "INSERT INTO product (id_product, name, product_category, product_design, product_size, stock, price, description) VALUES ('$idProduct','$name','$prodCategory','$prodDesign','$prodSize','$stock','$price', '$description')");
            array_push($queries, $query);
            $index++;
        }
        
        $result = ProductModel::saveByTransacction($queries);
        if (!$result) {
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Producto creado con exito");
    }
    public function savePromo($promoData)
    {   
        /*
            En este metodo no precisamos el ID del usuario, lo unico que validamos es que tenga un token y sea valido. 
            Si no tiene token, no pasa de la funcion para abajo por que el metodo mismo le niega el acceso.
        */
        $this->jwt->verifyTokenAndGetIdUserFromRequest();
        $bodyIsValid = $this->validateBodyOfPromo($promoData);
        if (!$bodyIsValid) {
            echo $this->response->error400('Error en los datos enviados');
            die();
        }

        $idProduct = $promoData['idProduct'];
        $name = $promoData['name'];
        $stock = $promoData['stock'];
        $price = $promoData['price'];
        $description = $promoData['description'];
        $contains = $promoData['contains'];
        $queries = array();
        $index = 0;

        //INSERCION DEL PRODUCTO PROMO Y OBTENCION DE SU BARCODE PARA isProduct
        //Valido que no exista el producto que se quiere agregar como promo
        $productPromoExist = ProductModel::getProductById($idProduct);
        if ($productPromoExist) {
            echo $this->response->error203("El promo $idProduct ya existe!");
            die();
        }

            $createPromo = ProductModel::createPromo($idProduct,$name,$stock,$price,$description);
            if(!$createPromo){
                echo $this->response->error500();
                die();
            }
            $getBarcode = ProductModel::getBarcodeByIdProduct($idProduct);
            if(!$getBarcode){
                echo $this->response->error203("Hubo un problema");       
                die();
            }
            //Obtengo el INT de la respuesta sql ARRAY
            $isProduct = intval($getBarcode['barcode']);
            
            
            //agrego productos a promo
            foreach ($contains as $contain) {
            $haveProduct = $contain['haveProduct'];
            $quantity = $contain['quantity'];
            
            //Valido que exista el producto que se agrega a la promo
            $productExist = ProductModel::getProductByBarcode($haveProduct);
            if (!$productExist) {
                echo $this->response->error203("El producto $haveProduct no existe");
                die();
            }
            //Valido que IsProduct no sea igual a haveProduct
            if ($isProduct == $haveProduct) {
                echo $this->response->error203("Error - Los Productos son iguales");
                die();
            }
            //Valido el estado del producto que se agrega a la promo
            $state = ProductModel::getStateOfProduct($haveProduct);
            if ($state["state"] == 0) {
                echo $this->response->error203("El producto $haveProduct esta INACTIVO");
                die();
            }            
            //Valido que cantidad a agregar no sea mayor a la cantidad disponible del producto
            $stockExist = ProductModel::getStockProductByBarcode($haveProduct);
            if ($quantity>$stockExist["stock"]) {
                
                echo $this->response->error203("No dispone de $quantity unidades para el producto $haveProduct");
                die();
            } 
            $query = array($index => "INSERT INTO promo (is_product, have_product, quantity) VALUES ($isProduct,$haveProduct, $quantity)");
            array_push($queries, $query);
            $index++;
        }
        $result = ProductModel::ProductsOfPromoTransacction($queries);
        if (!$result) {
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Promo creado con exito");
    }
    //CONSULTAS

    //Get consumido por parte del cliente 
    public function getModelsOfProduct($idProduct)
    {
        $product = ProductModel::getActiveModelsOfProductById($idProduct);
        if (!$product) {
            echo $this->response->error203("No| existe producto con ID = $idProduct");
            die();
        }
        //Data en comun
        $name = $product[0]["name"];
        $description = $product[0]["description"];
        //Array de modelos
        $models = array();
        foreach($product as $modelOfProduct){
            array_push( $models, array( "size" => $modelOfProduct['product_size'],"design" => $modelOfProduct['product_design'],"barcode" => $modelOfProduct["barcode"],"stock" => $modelOfProduct['stock'],"price" => $modelOfProduct["price"] ));
        }
        $response = array("name" => $name, "description" => $description, "models" => $models);

        echo $this->response->successfully("Productos obtenidos", $response);
    }
    //Get consumido por parte de la empresa
    public function getAllModelsOfProduct($idProduct)
    {
        $product = ProductModel::getAllModelsOfProductById($idProduct);
        if (!$product) {
            echo $this->response->error203("No existe producto con ID = $idProduct");
            die();
        }
        //Data en comun
        $name = $product[0]["name"];
        $description = $product[0]["description"];
        //Array de modelos
        $models = array();
        foreach($product as $modelOfProduct){
            array_push( $models, array( "size" => $modelOfProduct['product_size'],"design" => $modelOfProduct['product_design'],"barcode" => $modelOfProduct["barcode"],"stock" => $modelOfProduct['stock'],"price" => $modelOfProduct["price"],"state" => $modelOfProduct["state"] ));
        }
        $response = array("name" => $name, "description" => $description, "models" => $models);

        echo $this->response->successfully("Productos obtenidos", $response);
    }
    //TODOS LOS GETS DE PRODUCTOS
    public function getAllProducts()
    {
        $products = ProductModel::getAllProducts();
        echo $this->response->successfully("Todos los Productos del sistema:", $products);
        die();
    }
    //PRODUCTOS ACTIVOS
    public function getActiveProducts()
    {
        $products = ProductModel::getAllProductsActive();
        echo $this->response->successfully("Productos obtenidos:", $products);
        die();
    }
    //PRODUCTOS INACTIVOS
    public function getDisableProducts(){
        $products = ProductModel::getAllProductsDisable();
        echo $this->response->successfully("Productos Obtenidos:", $products);
        die();
    }
    //TODOS LOS GETS DE PROMOS
    public function getAllProductsOfPromo($idProduct){//CORREGIR
        $products = ProductModel::getAllProductsOfPromoById($idProduct);
        echo $this->response->successfully("Todos los productos de la promo:", $products);
        die();
    }
    public function getAllDisablePromos(){
        $products = ProductModel::getAllDisablePromos();
        echo $this->response->successfully("Todos las promos incativas", $products);
        die();
    }
    public function getAllActivePromos(){
        $products = ProductModel::getAllActivePromos();
        echo $this->response->successfully("Todos las promos activas:", $products);
        die();
    }
    //PRODUCTOS PARA LA CATEGORIA DE NOMBRE:
    public function getProductsByNameCategoy($nameCategory)
    {  
        $products = ProductModel::getProductsByNameCategory($nameCategory);
        echo $this->response->successfully("Productos obtenidos:", $products);
        die();
    }
    //PRODUCTOS PARA EL TALLE CON NOMBRE:
    public function getProductByNameSize($nameSize)
    {
        $products = ProductModel::getProductByName($nameSize);
        echo $this->response->successfully("Productos obtenidos:", $products);
        die();
    }
    //PRODUCTOS PARA EL DISEÑO CON NOMBRE:
    public function getProductByNameDesign($nameDesign)
    {
        $products = ProductModel::getProductsByNameDesign($nameDesign);
        echo $this->response->successfully("Productos obtenidos:", $products);
        die();
    }
    public function getProductsByName($name)
    {
        $products = ProductModel::getProductByName($name);
        echo $this->response->successfully("Productos obtenidos:", $products);
        die();
    }
    
    public function getProductByBarcode($barcode)
    {
        $product = ProductModel::getProductByBarcode($barcode);
        if (!$product) {
            echo $this->response->error203("No existe el producto con el codigo de barras $barcode");
            die();
        }
        echo $this->response->successfully("Producto obtenido", $product);
    }
    public function getStockByBarcode($barcode)
    {
        $quantity = ProductModel::getStockProductByBarcode($barcode);
        if ($quantity<0) {
            echo $this->response->error203("no dispone de disponible para $barcode");
            die();
        }
        echo $this->response->successfully("Stock", $quantity);
    }
    public function getStateByBarcode($barcode)
    {
        $quantity = ProductModel::getStateOfProduct($barcode);
        if ($quantity==0) {
            echo $this->response->error203("El producto se encuentra INACTIVO");
            die();
        }
        echo $this->response->successfully("El producto se encuentra ACTIVO");
    }
    
    //MODIFICACIONES
    public function updateProduct($barcode, $productData)
    {
        $this->jwt->verifyTokenAndGetIdUserFromRequest();
        $bodyIsValid = $this->validateBodyOfProduct($productData);
        if (!$bodyIsValid) {
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

        //Valido que exista el producto
        $productExist = ProductModel::getProductByBarcode($barcode);
        if (!$productExist) {
            echo $this->response->error203("Esta intentando modificar un producto que no existe");
            die();
        }
        //Valido que solo quiera actualizar atributos del producto
        $updateAttributes = ProductModel::identifyProduct($idProduct, $prodDesign, $prodSize);
        if ($updateAttributes) {
            $result = ProductModel::updateAttributesOfProduct($barcode, $name, $stock, $price, $description);
            echo $this->response->successfully("Producto actualizado con exito");
            die();
        }
        //Valido que exista la categoria a actualizar
        $categoryExist = CategoryModel::getCategoryById($prodCategory);
        if (!$categoryExist) {
            echo $this->response->error203("Esta intentando ingresar una categoria que no existe");
            die();
        }
        //Valido que exista el diseño a actualizar
        $designExist = DesignModel::getDesignById($prodDesign);
        if (!$designExist) {
            echo $this->response->error203("Esta intentando ingresar un diseño que no existe");
            die();
        }
        //Valido que exista el talle a actualizar
        $sizeExist = SizeModel::getSizeById($prodSize);
        if (!$sizeExist) {
            echo $this->response->error203("Esta intentando ingresar una talle que no existe");
            die();
        }
        $result = ProductModel::updateProduct($barcode, $idProduct, $name, $prodCategory, $prodDesign, $prodSize, $stock, $price, $description);
        if (!$result) {
            echo $this->response->error500();
        }
        echo $this->response->successfully("Producto actualizado con exito");
    }
    public function updateLineOfProducts($idProduct, $productData)
    {
        $this->jwt->verifyTokenAndGetIdUserFromRequest();
        $bodyIsValid = $this->validateBodyOfAtribute($productData);
        if (!$bodyIsValid) {
            echo $this->response->error400('Error en los datos enviados');
            die();
        }
        $name = $productData['name'];
        $prodCategory = $productData['prodCategory'];
        $price = $productData['price'];
        $description = $productData['description'];

        //Valido que el prod exista
        $updateLineAttributes = ProductModel::getProductById($idProduct);
        if (!$updateLineAttributes) {
            echo $this->response->error203("Esta intentando editar una linea de productos que no existe");
            die();
        }
        //Valido que exista la categoria a actualizar
        $categoryExist = CategoryModel::getCategoryById($prodCategory);
        if (!$categoryExist) {
            echo $this->response->error203("Esta intentando ingresar una categoria que no existe");
            die();
        }
        
        $result = ProductModel::updateProductLineAttributes($idProduct, $name, $prodCategory, $price, $description);
        if (!$result) {
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Linea de Productos actualizada exitosamente");
    }
    //ELIMINAR
    public function disableProduct($barcode)
    {
        $this->jwt->verifyTokenAndGetIdUserFromRequest();
        //Valido que exista el producto
        $productExist = ProductModel::getProductByBarcode($barcode);
        if (!$productExist) {
            echo $this->response->error203("Esta intentando deshabilitar un producto que no existe");
            die();
        }
        $result = ProductModel::disableProduct($barcode);
        if (!$result) {
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Producto deshabilitado exitosamente");
    }
    public function activeProduct($barcode)
    {
        $this->jwt->verifyTokenAndGetIdUserFromRequest();
        //Valido que exista el producto
        $productExist = ProductModel::getProductByBarcode($barcode);
        if (!$productExist) {
            echo $this->response->error203("Esta intentando activar un producto que no existe");
            die();
        }
        $result = ProductModel::activeProduct($barcode);
        if (!$result) {
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Producto activado exitosamente");
    }
    public function disableLineOfProduct($idProduct)
    {
        $this->jwt->verifyTokenAndGetIdUserFromRequest();
        //Valido que exista el producto
        $productExist = ProductModel::getProductById($idProduct);
        if (!$productExist) {
            echo $this->response->error203("Esta intentando deshabilitar un linea de productos que no existe");
            die();
        }
        $result = ProductModel::disableLineOfProduct($idProduct);
        if (!$result) {
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Linea de Productos deshabilitados exitosamente");
    }
    public function activeLineOfProduct($idProduct)
    {
        $this->jwt->verifyTokenAndGetIdUserFromRequest();
        //Valido que exista el producto
        $productExist = ProductModel::getProductById($idProduct);
        if (!$productExist) {
            echo $this->response->error203("Esta intentando activar una linea de productos que no existe");
            die();
        }
        $result = ProductModel::activeLineOfProduct($idProduct);
        if (!$result) {
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Linea de Productos activados exitosamente");
    }
}
