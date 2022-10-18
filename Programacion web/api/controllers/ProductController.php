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

    private function validateBodyOfProduct($productData){
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
    private function validateBodyOfUpdateModel($productData){
        if (!isset($productData['name'])
            ||  !isset($productData['prodDesign'])
            ||  !isset($productData['prodSize'])
            ||  !isset($productData['stock'])
            ||  !isset($productData['description'])
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
    private function validateBodyOfPromo($promoData)
    {
        if (
            !isset($promoData['idProduct'])
            ||  !isset($promoData['name'])
            ||  !isset($promoData['stock'])
            ||  !isset($promoData['price'])
            ||  !isset($promoData['description'])
            ||  !isset($promoData['contains'])
        ) return false;

        return $promoData;
    }
    private function validateBodyOfUpdatePromo($promoData)
    {
        if (
            !isset($promoData['name'])
            ||  !isset($promoData['stock'])
            ||  !isset($promoData['price']) 
            ||  !isset($promoData['description'])
        ) return false;
        $validStock = $promoData['stock'];
        if($validStock<0){
            return false;
        }
        return $promoData;
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
        $bodyIsValid = $this->validateBodyOfPromo($promoData);  //EL CUERPO DE LA PROMO ES DIFERENTE
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

        //SE DEBE INSERTAR EL PRODUCTO PARA OBTENER BARCODE
        //Valido que no exista
        $productPromoExist = ProductModel::getBarcodeById($idProduct);
        if ($productPromoExist) {
            echo $this->response->error203("El producto $idProduct ya existe!");
            die();
        }

        $createPromo = ProductModel::createPromo($idProduct,$name,$stock,$price,$description);
        if(!$createPromo){
            echo $this->response->error500();
            die();
        }
        //buscaba el barcode del idproduc (las promos no pueden tener modelos por eso barcode e idProd es una relacion 1a1)
        $getBarcode = ProductModel::getBarcodeById($idProduct);
        if(!$getBarcode){
            echo $this->response->error203("No se en cuentra el codigo para $idProduct");       
            die();
        }
        //Obtengo el INT de la respuesta sql ARRAY asociativo
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
            //Valido el estado del producto que se agrega a la promo
            $state = ProductModel::getStateOfProduct($haveProduct);
            if ($state["state"] == 0) {
                echo $this->response->error203("El producto $haveProduct esta INACTIVO");
                die();
            }            
            //Valido que cantidad a agregar no sea mayor a la cantidad disponible del producto
            $stockExist = ProductModel::getStockProductByBarcode($haveProduct);
            //ACA A QUANTITY LO TENGO QUE RECIBIR YA MULTIPLICADO O HACER if (QUNATITY*STOCK > STOCKeXIST)
            if (($quantity*$stock)>$stockExist["stock"]) {    
                echo $this->response->error203("No dispone de tantas unidades en el producto $haveProduct");
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
    public function getModelsOfProduct($idProduct)
    {
        $product = ProductModel::getActiveModelsOfProductById($idProduct);
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
            array_push( $models, array( "size" => $modelOfProduct['size'],"design" => $modelOfProduct['design'],"barcode" => $modelOfProduct["barcode"],"stock" => $modelOfProduct['stock'],"price" => $modelOfProduct["price"] ));
        }
        $response = array("name" => $name, "description" => $description, "models" => $models);

        echo $this->response->successfully("Productos obtenidos", $response);
    }
    //Get consumido por parte de la empresa PRODUCTOS ACTIVOS E INACTIVOS
    public function getAllModelsOfProduct($idProduct){
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
            array_push( $models, array( "size" => $modelOfProduct['size'],"design" => $modelOfProduct['design'],"barcode" => $modelOfProduct["barcode"],"stock" => $modelOfProduct['stock'],"price" => $modelOfProduct["price"],"state" => $modelOfProduct["state"] ));
        }
        $response = array("name" => $name, "description" => $description, "models" => $models);

        echo $this->response->successfully("Productos obtenidos", $response);
    }
    public function getAllProducts()
    {
        $products = ProductModel::getAllProducts();
        echo $this->response->successfully("Todos los Productos del sistema:", $products);
        die();
    }
    public function getAllPromos()
    {
        $products = ProductModel::getAllPromos();
        echo $this->response->successfully("Todas las Promos:", $products);
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
    public function getProductsOfPromo($idProduct){
        $product = ProductModel::getProductsOfPromoById($idProduct);
        if (!$product) {
            echo $this->response->error203("No existe promo con ID:$idProduct");
            die();
        }
        //Data en comun
        $barcodePromo = $product[0]["barcodePromo"];
        $namePromo = $product[0]["namePromo"];
        $stockPromo = $product[0]["stockPromo"];        
        //Array de productos en promo
        $products = array();
        foreach($product as $prodInPromo){
            array_push( $products, array("barcodeProd" => $prodInPromo['barcodeProd'],
            "quantity" => $prodInPromo['quantity'],
            "idProduct" => $prodInPromo['idProduct'],
            "name" => $prodInPromo['name'],
            "design" => $prodInPromo['design'],
            "size" => $prodInPromo['size']));
        }
        $response = array("barcodePromo" => $barcodePromo, "namePromo" => $namePromo,"stockPromo" => $stockPromo, "products" => $products);

        echo $this->response->successfully("Productos obtenidos", $response);
    }
    public function getAllProductsOfPromo($idProduct){
        $product = ProductModel::getAllProductsOfPromoById($idProduct);
        if (!$product) {
            echo $this->response->error203("No existe promo con ID:$idProduct");
            die();
        }
        //Data en comun
        $barcodePromo = $product[0]["barcodePromo"];
        $namePromo = $product[0]["namePromo"];
        $stockPromo = $product[0]["stockPromo"];        
        //Array de productos en promo
        $products = array();
        foreach($product as $prodInPromo){
            array_push( $products, array("barcodeProd" => $prodInPromo['barcodeProd'],
            "quantity" => $prodInPromo['quantity'],
            "idProduct" => $prodInPromo['idProduct'],
            "name" => $prodInPromo['name'],
            "design" => $prodInPromo['design'],
            "size" => $prodInPromo['size']));
        }
        $response = array("barcodePromo" => $barcodePromo, "namePromo" => $namePromo,"stockPromo" => $stockPromo, "products" => $products);

        echo $this->response->successfully("Productos obtenidos", $response);
    }
    //PRODUCTOS PARA LA CATEGORIA DE NOMBRE:
    public function getProductByNameCategoy($nameCategory)
    {  
        $products = ProductModel::getProductsByNameCategory($nameCategory);
        echo $this->response->successfully("Productos obtenidos:", $products);

    }
    //PRODUCTOS PARA EL TALLE CON NOMBRE:
    public function getProductByNameSize($nameSize)
    {
        $products = ProductModel::getProductsByNameSize($nameSize);
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
    public function getProductByName($name){
        $products = ProductModel::getProductByName($name);
        echo $this->response->successfully("Productos obtenidos:", $products);
        die();
    }
    public function getAllProductByName($name){
        $products = ProductModel::getAllProductByName($name);
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
    public function updateModel($barcode, $productData)
    {
        $this->jwt->verifyTokenAndGetIdUserFromRequest();
        $bodyIsValid = $this->validateBodyOfUpdateModel($productData);
        if (!$bodyIsValid) {
            echo $this->response->error400('Error en los datos enviados');
            die();
        }
        $name = $productData['name'];
        $prodDesign = $productData['prodDesign'];
        $prodSize = $productData['prodSize'];
        $stock = $productData['stock'];
        $description = $productData['description'];

        //Valido que exista el producto
        $productExist = ProductModel::getProductByBarcode($barcode);
        if (!$productExist) {
            echo $this->response->error203("Esta intentando modificar un modelo que no existe");
            die();
        }
        //Valido que solo quiera actualizar atributos del producto
        $updateAttributes = ProductModel::identifyModel($barcode,$prodDesign, $prodSize);
        if ($updateAttributes) {
            $result = ProductModel::updateModel($barcode, $name,$prodDesign,$prodSize, $stock, $description);
            echo $this->response->successfully("Modelo actualizado con exito");
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
        $result = ProductModel::updateModel($barcode, $name,$prodDesign, $prodSize, $stock, $description);
        if (!$result) {
            echo $this->response->error500();
        }
        echo $this->response->successfully("Modelo actualizado con exito");
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
        $updateLineAttributes = ProductModel::getBarcodeById($idProduct);
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
    public function updatePromo($idProduct, $promoData){
        $this->jwt->verifyTokenAndGetIdUserFromRequest();
        $bodyIsValid = $this->validateBodyOfUpdatePromo($promoData);
        if (!$bodyIsValid) {
            echo $this->response->error400('Error en los datos enviados');
            die();
        }
        $name = $promoData['name'];
        $stock = $promoData['stock'];
        $price = $promoData['price'];
        $description = $promoData['description'];
        
        //Valido que el prod exista
        $promoExist = ProductModel::getBarcodeById($idProduct);
        if (!$promoExist) {
            echo $this->response->error203("Esta intentando editar una promo que no existe");
                die();
        }
        $barcodePromo = $promoExist['barcode'];
        $getStock= ProductModel::getStockProductByBarcode($barcodePromo);
        $stockNow = intval($getStock['stock']);
        $increse = $stock - $stockNow;
        intval($increse);

        if($increse<0){

            $decrease=$increse * -1;
            intval($decrease);
            $stockOfProductsByPromo = ProductModel::productsAndQuantityAsPromo($idProduct);
            foreach ($stockOfProductsByPromo as $prodAndStock) {
                $barcode = $prodAndStock['have_product'];
                $units = $prodAndStock['quantity'];    
                $addUnits = $units * $decrease;
                intval($addUnits);
                $addToStock = ProductModel::UpdateMoreStockProductsOfPromo($barcode,$addUnits);
                if(!$addToStock){
                    echo $this->response->error203("Hubo un problema actualizando el stock de los productos");
                    die();
                }
                $units = 0;    
            }
            $result = ProductModel::updatePromo($idProduct, $name, $stock, $price, $description);
            if (!$result) {
                echo $this->response->error500();
                die();
            }
            echo $this->response->successfully("Deshizo $decrease Unidades de Promo exitosamente");
        }    
        if($increse>=0){
            $stockOfProductsByPromo = ProductModel::productsAndQuantityAsPromo($idProduct);
            foreach ($stockOfProductsByPromo as $prodAndStock) {
                $barcode = $prodAndStock['have_product'];
                $units = $prodAndStock['quantity'];
                $unitsNecesary = $units * $increse;
                intval($unitsNecesary);
                $validateStock = ProductModel::checkStock($barcode,$unitsNecesary);
                if (!$validateStock){
                    echo $this->response->error203("No dispone de $unitsNecesary en el producto $barcode para actualizar la promo");
                    die();
                }
                $editStock = ProductModel::UpdateStockProductsOfPromo($barcode,$unitsNecesary);
                if(!$editStock){
                    echo $this->response->error203("Hubo un problema actualizando el stock de los productos");
                die();
                }
                $units = 0;
            }
            $result = ProductModel::updatePromo($idProduct, $name, $stock, $price, $description);
                if (!$result) {
                    echo $this->response->error500();
                die();
                }
        echo $this->response->successfully("Promo actualizada exitosamente");
        }
    }
    

    //ELIMINAR LOGICO
    public function disableModel($barcode){
        $this->jwt->verifyTokenAndGetIdUserFromRequest();
        //Valido que exista el producto
        $productExist = ProductModel::getProductByBarcode($barcode);
        if (!$productExist) {
            echo $this->response->error203("Esta intentando deshabilitar un modelo que no existe");
            die();
        }
        $result = ProductModel::disableModel($barcode);
        if (!$result) {
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("modelo deshabilitado exitosamente");
    }
    public function activeModel($barcode)
    {
        $this->jwt->verifyTokenAndGetIdUserFromRequest();
        //Valido que exista el producto
        $productExist = ProductModel::getProductByBarcode($barcode);
        if (!$productExist) {
            echo $this->response->error203("Esta intentando activar un modelo que no existe");
            die();
        }
        $result = ProductModel::activeModel($barcode);
        if (!$result) {
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("modelo activado exitosamente");
    }
    public function disableLineOfProduct($idProduct)
    {
        $this->jwt->verifyTokenAndGetIdUserFromRequest();
        //Valido que exista el producto
        $productExist = ProductModel::getBarcodeById($idProduct);
        if (!$productExist) {
            echo $this->response->error203("Esta intentando deshabilitar un linea de productos que no existe");
            die();
        }
        $result = ProductModel::disableLineOfProduct($idProduct);
        if (!$result) {
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Linea de productos deshabilitados exitosamente");
    }
    public function activeLineOfProduct($idProduct){
        $this->jwt->verifyTokenAndGetIdUserFromRequest();
        //Valido que exista el producto
        $productExist = ProductModel::getBarcodeById($idProduct);
        if (!$productExist) {
            echo $this->response->error203("Esta intentando activar una linea de prodcutos que no existe");
            die();
        }
        $result = ProductModel::activeLineOfProduct($idProduct);
        if (!$result) {
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Linea de productos activados exitosamente");
    }
}
