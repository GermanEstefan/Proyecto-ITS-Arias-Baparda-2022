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
            ||  !isset($productData['picture'])
            ||  !isset($productData['models'])
        ) return false;
             
        return $productData;
    }
    private function validateBodyOfUpdateModel($productData){
        if (!isset($productData['prodDesign'])
            ||  !isset($productData['prodSize'])
            ||  !isset($productData['stock'])
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
            ||  !isset($promoData['picture'])
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
        $picture = $productData['picture'];
        $models = $productData['models'];
        if($price<0){
            echo $this->response->error203("El precio $price es incorrecto");
            die();
        }
        if($prodCategory == 1){
            echo $this->response->error203("Un producto no puede tener categoria $prodCategory");
            die();
        }
        $queries = array();
        $index = 0;

        foreach ($models as $model) {
            $prodDesign = $model['prodDesign'];
            $prodSize = $model['prodSize'];
            $stock = $model['stock'];
            if($stock<0){
                echo $this->response->error203("El stock $stock es incorrecto");
                die();
            }
            //Valido que exista la categoria
            $categoryExist = CategoryModel::getCategoryById($prodCategory);
            if (!$categoryExist) {
                echo $this->response->error203("La categoria con el ID: $prodCategory no existe");
                die();
            }
            //Valido que exista el diseño
            $designExist = DesignModel::getDesignById($prodDesign);
            $analyzeDesign = $designExist['id_design'];
            if (!$designExist) {
                echo $this->response->error203("El diseño con el ID: $prodDesign no existe");
                die();
            }elseif($analyzeDesign == 1){
                echo $this->response->error203("Un producto no puede tener el diseño $prodDesign por que no es Promo");
                die();
            }
            //Valido que exista el talle
            $sizeExist = SizeModel::getSizeById($prodSize);
            $analyzeSize = $sizeExist['id_size'];
            if (!$sizeExist) {
                echo $this->response->error203("El talle con el ID: $prodSize no existe");
                die();
            }elseif($analyzeSize == 1){
                echo $this->response->error203("Un producto no puede tener el talle $prodSize por que no es Promo");
                die();
            } 
            //Valido que no exista el producto
            $productExist = ProductModel::identifyProduct($idProduct, $prodDesign, $prodSize);
            if ($productExist) {
                echo $this->response->error203("Esta intentando ingresar un producto ya existente");
                die();
            }
            $query = array($index => "INSERT INTO product (id_product, name, product_category, product_design, product_size, stock, price, description, picture) VALUES ('$idProduct','$name','$prodCategory','$prodDesign','$prodSize','$stock','$price', '$description', '$picture')");
            array_push($queries, $query);
            $index++;
        }
        
        $result = ProductModel::saveByTransacction($queries);
        if (!$result) {
            echo $this->response->error203("No se puede crear el produco. REVISE LOS VALORES");
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
        $picture = $promoData['picture'];
        $contains = $promoData['contains'];
        
        if($stock<0){
            echo $this->response->error203("El stock $stock es incorrecto");
            die();
        }
        if($price<0){
            echo $this->response->error203("El precio $price es incorrecto");
            die();
        }
        //Valido que no exista
        $productPromoExist = ProductModel::getBarcodeById($idProduct);
        if ($productPromoExist) {
            echo $this->response->error203("El producto $idProduct ya existe!");
            die();
        }
        $createPromo = ProductModel::createPromo($idProduct,$name,$stock,$price,$description,$picture,$contains);
        if(!$createPromo){
            echo $this->response->error203("No se puede crear la promo. revise los valores" );
            die();
        }
        echo $this->response->successfully("Promo creado con exito");
    }
    //Consultas
    
    
    //CONSULTAS DESDE EL CLIENTE
    public function getProductByName($name){
        $products = ProductModel::getProductByName($name);
        echo $this->response->successfully("Lista de productos con nombre $name:", $products);
        die();
    }
    public function getProductByNameCategoy($nameCategory){  
        $products = ProductModel::getProductsByNameCategory($nameCategory);
        echo $this->response->successfully("Lista de productos para la categoria $nameCategory:", $products);

    }
    public function getModelsOfProduct($idProduct){
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

        echo $this->response->successfully("Lista de modelos para $name", $response);
    }
    public function getActiveProducts(){
        $products = ProductModel::getAllProductsActive();
        echo $this->response->successfully("Listado de Todos los productos:", $products);
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

        echo $this->response->successfully("Productos que integran la Promo: $namePromo", $response);
    }
    public function getProductByBarcode($barcode){
        $product = ProductModel::getProductByBarcode($barcode);
        if (!$product) {
            echo $this->response->error203("No existe el producto con el codigo de barras $barcode");
            die();
        }
        echo $this->response->successfully("Producto encontrado", $product);
    }
    public function getActivePromos(){
        $products = ProductModel::getPromosBO();
        echo $this->response->successfully("Listado de Todas las PROMOS:", $products);
        die();
    }


    //DESDE BackOffice
    public function getProductByNameBO($name){
        $products = ProductModel::getProductByNameBO($name);
        echo $this->response->successfully("Todos los productos del sistema de nombre $name:", $products);
        die();
    }
    public function getProductByBarcodeBO($barcode){
        $product = ProductModel::getProductByBarcodeBO($barcode);
        if (!$product) {
            echo $this->response->error203("No existe el producto con el codigo de barras $barcode");
            die();
        }
        echo $this->response->successfully("Porducto identificado $barcode", $product);
    }
    public function getModelsOfProductBO($idProduct){
        $product = ProductModel::getModelsOfProductByIdBO($idProduct);
        if (!$product) {
            echo $this->response->error203("No existe producto con ID = $idProduct");
            die();
        }
        //Data en comun
        $id = $product[0]["id"];
        $name = $product[0]["name"];
        $description = $product[0]["description"];
        $price = $product[0]["price"];
        $category = $product[0]["category"];
        //Array de modelos
        $models = array();
        foreach($product as $modelOfProduct){
            array_push( $models, array( "size" => $modelOfProduct['size'],"design" => $modelOfProduct['design'],"barcode" => $modelOfProduct["barcode"],"stock" => $modelOfProduct['stock'],"state" => $modelOfProduct['state'] ));
        }
        $response = array("id" => $id,"name" => $name, "description" => $description,"price" => $price,"category" => $category, "models" => $models);

        echo $this->response->successfully("TODOS los modelos para $name", $response);
    }
    public function getPromosBO(){
        $products = ProductModel::getPromosBO();
        echo $this->response->successfully("Todas las Promos del Sistema:", $products);
        die();
    }
    public function getProductsBO(){
        $products = ProductModel::getProductsBO();
        echo $this->response->successfully("Todos los Productos del sistema:", $products);
        die();
    }
    public function getProductsOfProductBO($idProduct){
        $product = ProductModel::getProductsOfProductBO($idProduct);
        if (!$product) {
            echo $this->response->error203("No existe promo con ID:$idProduct");
            die();
        }
        //Data en comun
        $barcodePromo = $product[0]["barcodePromo"];
        $namePromo = $product[0]["namePromo"];
        $stockPromo = $product[0]["stockPromo"];        
        $state = $product[0]["state"];        
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
        $response = array("barcodePromo" => $barcodePromo, "namePromo" => $namePromo,"stockPromo" => $stockPromo,"state" => $state,"products" => $products);

        echo $this->response->successfully("Productos que integran la Promo: $namePromo", $response);
    }
    public function productsDisableBO(){
        $products = ProductModel::getProductsDisableBO();
        echo $this->response->successfully("Productos en estado INACTIVO:", $products);
        die();
    }
    public function promosDisableBO(){
        $products = ProductModel::getPromosDisableBO();
        echo $this->response->successfully("Promos en estado INACTIVO:", $products);
        die();
    }
    public function getOnlyProducts(){
        $products = ProductModel::getOnlyProducts();
        echo $this->response->successfully("Listado de Todos los productos:", $products);
        die();
    }
    public function getSuggestIdPromoBO(){
        $products = ProductModel::getSuggestIdByPromos();
        $suggest = $products["id_product"]+1;
        echo $this->response->successfully("Promo sugerida:", $suggest);
        die();
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
    public function getStockByBarcode($barcode){
        $quantity = ProductModel::getStockProductByBarcode($barcode);
        if ($quantity<0) {
            echo $this->response->error203("no dispone de disponible para $barcode");
            die();
        }
        echo $this->response->successfully("Stock", $quantity);
    }
    public function getStateByBarcode($barcode){
        $quantity = ProductModel::getStateOfProduct($barcode);
        if ($quantity==0) {
            echo $this->response->error203("El producto se encuentra INACTIVO");
            die();
        }
        echo $this->response->successfully("El producto se encuentra ACTIVO");
    }
    public function getIdProductByBarcode($barcode){
        $quantity = ProductModel::identifyProductByBarcode($barcode);
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
        $prodDesign = $productData['prodDesign'];
        $prodSize = $productData['prodSize'];
        $stock = $productData['stock'];
        if($stock<0){
            echo $this->response->error203("El stock $stock es incorrecto");
            die();
        }
        //Valido que exista el producto
        $productExist = ProductModel::identifyProductByBarcode($barcode);
        if (!$productExist) {
            echo $this->response->error203("Esta intentando modificar un modelo que no existe");
            die();
        }
        $idProduct = $productExist["id_product"];

        //Valido que solo quiera actualizar atributos del producto
        $updateAttributes = ProductModel::identifyModel($barcode,$prodDesign, $prodSize);
        if ($updateAttributes) {
            $result = ProductModel::updateModel($barcode,$prodDesign,$prodSize, $stock);
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
        //Valido que no quiera oingresr un modelo que ya existe
        $modelExist = ProductModel::identifyProduct($idProduct,$prodDesign,$prodSize);
        if ($modelExist) {
            echo $this->response->error203("Esta intentando ingresar un Modelo que ya existe");
            die();
        }
        $result = ProductModel::updateModel($barcode,$prodDesign, $prodSize, $stock);
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
        $picture = $productData['picture'];
        if($price<0){
            echo $this->response->error203("El precio $price es incorrecto");
            die();
        }
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
        //Valido que no ingrese a categoria promo
        if ($prodCategory == 1) {
            echo $this->response->error203("Esta intentando ingresar un producto en la categoria PROMO");
            die();
        }
        
        $result = ProductModel::updateProductLineAttributes($idProduct, $name, $prodCategory, $price, $description, $picture);
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
        $picture = $promoData['picture'];
        $description = $promoData['description'];
        if($stock<0){
            echo $this->response->error203("El stock $stock es incorrecto");
            die();
        }
        if($price<0){
            echo $this->response->error203("El precio $price es incorrecto");
            die();
        }
        //Valido que el prod exista
        $promoExist = ProductModel::getBarcodeById($idProduct);
        if (!$promoExist) {
            echo $this->response->error203("Esta intentando editar una promo que no existe");
                die();
        }
        $barcodePromo = $promoExist['barcode'];
        $getStock= ProductModel::getStockProductByBarcode($barcodePromo);
        $stockNow = $getStock['stock'];
        $diference = $stock - $stockNow;

        if($diference<0){

            //promo descrese, agregar stock a productos
            $stockOfProductsByPromo = ProductModel::productsAndQuantityAsPromo($idProduct);
            foreach ($stockOfProductsByPromo as $prodAndStock) {
                $barcode = $prodAndStock['have_product'];
                $units = $prodAndStock['quantity'];    
                $addUnits = ($units * $diference)*-1;
                $addToStock = ProductModel::UpdateMoreStockProductsOfPromo($barcode,$addUnits);
                if(!$addToStock){
                    echo $this->response->error203("Hubo un problema actualizando el stock de los productos");
                    die();
                }
                $units = 0;    
            }
            $result = ProductModel::updatePromo($idProduct, $name, $stock, $price, $description, $picture);
            if (!$result) {
                echo $this->response->error500();
                die();
            }
            echo $this->response->successfully("Deshizo $diference Unidades de Promo exitosamente");
        }    
        if($diference>=0){
            $stockOfProductsByPromo = ProductModel::productsAndQuantityAsPromo($idProduct);
            foreach ($stockOfProductsByPromo as $prodAndStock) {
                $barcode = $prodAndStock['have_product'];
                $units = $prodAndStock['quantity'];
                $unitsNecesary = $units * $diference;
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
            $result = ProductModel::updatePromo($idProduct, $name, $stock, $price, $description,$picture);
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
        $productExist = ProductModel::getProductByBarcodeBO($barcode);
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
        $productExist = ProductModel::getProductByBarcodeBO($barcode);
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
