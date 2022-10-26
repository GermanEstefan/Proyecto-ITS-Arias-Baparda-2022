<?php
    include_once('./helpers/Response.php');
    require_once("./database/Connection.php");
    class ProductModel extends Connection {

        protected $idProduct;
        protected $name;
        protected $prodCategory;
        protected $prodDesign;
        protected $prodSize;
        protected $stock;
        protected $price;
        protected $description;

        function __construct($idProduct,$name,$prodCategory,$prodDesign,$prodSize,$stock,$price,$description){
            $this->idProduct = $idProduct;
            $this->name = $name;
            $this->prodCategory = $prodCategory;
            $this->prodDesign = $prodDesign;
            $this->prodSize = $prodSize;
            $this->stock = $stock;
            $this->price = $price;
            $this->description = $description;
            parent::__construct();
        }
        //VALIDACION Solo sirve para validar que el producto exista pasandole un ID
        public static function productsAndQuantityAsPromo($idProduct){
            $conecction = new Connection();
            $query = "SELECT have_product, quantity
            FROM promo pr,product p
            WHERE p.id_product = $idProduct
            AND p.barcode = pr.is_product";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }
        public static function checkStock($barcode,$unitsNecesary){
            $conecction = new Connection();
            $query = "SELECT stock 
            FROM product p
            WHERE p.barcode = $barcode
            AND p.stock >= $unitsNecesary";
            return $conecction->getData($query)->fetch_assoc();
        }
        
        //VALIDACION Solo sirve para validar que la promo exista pasandole un ID
        public static function getBarcodeById($idProduct){
            $conecction = new Connection();
            $query = "SELECT barcode FROM product WHERE id_product = '$idProduct' limit 1";
            return $conecction->getData($query)->fetch_assoc();
        }
        //VALIDACION Solo se usa para validar cantidad de unidades disponibles para agregar un producto a una promo
        public static function getStockProductByBarcode($barcode){
            $conecction = new Connection();
            $query = "SELECT stock from product WHERE barcode = $barcode";
            return $conecction->getData($query)->fetch_assoc();
        }
        //VALIDACION Solo se usa para obtener el barcode UNICAMENTE para un idProd PROMO.
        public static function getBarcodeOfPromoByIdProduct($idProduct){
            $conecction = new Connection();
            $query = "SELECT barcode from product WHERE id_product = $idProduct";
            return $conecction->getData($query)->fetch_assoc();
        }
        //VALIDACION Solo sirve para que al momento de querer eliminar una categoria sepamos si esta en uso
        public static function getProductsByIdCategory($idCategory){
            $conecction = new Connection();
            $query = "SELECT barcode from product 
            where product_category = '$idCategory'";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }
        //VALIDACION Solo sirve para que al momento de querer eliminar un diseño sepamos si esta en uso
        public static function getProductsByIdDesign($idDesign){
            $conecction = new Connection();
            $query = "SELECT barcode from product
            where product_design = '$idDesign'";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }
        //VALIDACION Solo sirve para que al momento de querer eliminar un talle sepamos si esta en uso
        public static function getProductsByIdSize($idSize){
            $conecction = new Connection();
            $query = "SELECT barcode FROM product 
            where product_size = '$idSize'";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }
        
        //CONSULTAS Chekeadas

        //1-cliente consulta por nombre del producto (no devuelve promos)
        public static function getProductByName($name){
            $conecction = new Connection();
            $query = "SELECT distinct
            p.id_product,
            p.name,
            p.price,
            p.description,
            p.picture
            from product p
            where p.name LIKE'%$name%'
            AND p.product_category != 1
            AND p.state = 1";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }
        //2-cliente consulta productos de una categoria (no incluye promos)
        public static function getProductsByNameCategory($nameCategory){
            $conecction = new Connection();
            $query = "SELECT distinct
            p.id_product,
            p.name,
            p.price,
            p.description,
            p.picture
            FROM product p
            INNER JOIN category c
            on p.product_category = c.id_category 
            AND p.state = 1
            AND p.product_category != 1 
            AND c.name = '$nameCategory'";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }
        //3-Cliente selecciona un producto y consulta sus modelos ACTIVOS
        public static function getActiveModelsOfProductById($idProduct){
            $conecction = new Connection();
            $query = "SELECT p.barcode,
            p.name,
            d.name as design,
            s.name as size,
            p.price,
            p.stock,
            p.description,
            p.picture 
            from product p
            INNER JOIN design d
            INNER JOIN size s
            on p.product_design = d.id_design 
            AND p.product_size = s.id_size  
            AND p.id_product = '$idProduct'
            AND p.state = 1";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }
        //4-Cliente quiere ver todos los productos (no incluye promos)
        public static function getAllProductsActive(){
            $conecction = new Connection();
            $query = "SELECT distinct
            p.id_product,
            p.name,
            p.price,
            p.description,
            p.state 
            from product p
            where p.state = 1
            AND p.product_category != 1";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }
        //5-Cliente selecciona una promo y quiere ver los productos que la integra
        public static function getProductsOfPromoById($idProduct){
            $conecction = new Connection();
            $query = "SELECT
            pr.is_product as barcodePromo,
            p1.name as namePromo,
            p1.stock as stockPromo,
            pr.have_product as barcodeProd,
            pr.quantity,
            p2.id_product as idProduct,
            p2.name as name,
            d.name as design,
            s.name as size
            FROM promo pr, product p1, product p2, design d, size s
            WHERE p1.barcode = pr.is_product 
            AND p2.barcode = pr.have_product
            AND p2.product_design = d.id_design
            AND p2.product_size = s.id_size
            AND p1.id_product ='$idProduct'
            AND p1.state = 1";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }
        //6-Cliente o nacho consulta un producto ACTIVO por barcode
        public static function getProductByBarcode($barcode){
            $conecction = new Connection();
            $query = "SELECT p.barcode,
            p.id_product,
            p.name, 
            d.name as design,
            s.name as size,
            p.price,
            p.stock,
            c.name as category,
            p.description,
            p.state, 
            p.picture 
            FROM product p
            INNER JOIN category c
            INNER JOIN design d
            INNER JOIN size s
            on p.product_category = c.id_category 
            AND p.product_design = d.id_design 
            AND p.product_size = s.id_size  
            AND p.barcode = '$barcode'
            AND p.state = 1";
            return $conecction->getData($query)->fetch_assoc();
        }
         //7-Cliente consulta por todas las promos
         public static function getPromosBO(){
            $conecction = new Connection();
            $query = "SELECT distinct
            p.id_product,
            p.name,
            p.price,
            p.description,
            p.state, 
            p.picture
            from product p
            where p.state = 1
            AND p.product_category = 1";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }


        //BACKOFFICE
        // A- Obtener cualquier producto por nombre (no incluye promos)
        public static function getProductByNameBO($name){
            $conecction = new Connection();
            $query = "SELECT distinct
            p.id_product,
            p.name,
            p.price,
            p.description,
            p.state 
            from product P
            where p.name LIKE'%$name%'";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }
        //b - Obtener cualquier producto por barcode (incluye promos)
        public static function getProductByBarcodeBO($barcode){
            $conecction = new Connection();
            $query = "SELECT p.barcode,
            p.id_product,
            p.name,
            p.product_design as idDesign, 
            d.name as design,
            p.product_size as idSize,
            s.name as size,
            p.price,
            p.stock,
            c.name as category,
            p.description,
            p.state 
            FROM product p
            INNER JOIN category c
            INNER JOIN design d
            INNER JOIN size s
            on p.product_category = c.id_category 
            AND p.product_design = d.id_design 
            AND p.product_size = s.id_size  
            AND p.barcode = '$barcode'";
            return $conecction->getData($query)->fetch_assoc();
        }
        public static function getModelsOfProductByIdBO($idProduct){
            $conecction = new Connection();
            $query = "SELECT p.barcode,
            p.id_product AS id,
            p.name,
            d.name as design,
            s.name as size,
            p.price,
            p.product_category as category,
            p.stock,
            p.description,
            p.state,
            p.picture
            from product p
            INNER JOIN design d
            INNER JOIN size s
            on p.product_design = d.id_design 
            AND p.product_size = s.id_size  
            AND p.id_product = '$idProduct'";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }
        public static function getAllPromosBO(){
            $conecction = new Connection();
             $query = "SELECT
             pr.is_product as CodBarraPROMO,
             p1.name as namePromo,
             p1.stock as stockPromo,
             p1.state as state
             FROM promo pr, product p1
             WHERE p1.barcode = pr.is_product" ;
             return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }
        public static function getProductsBO(){
            $conecction = new Connection();
            $query = "SELECT 
            p.barcode,
            p.id_product,
            p.name,
            p.product_design as idDesign,
            d.name as design,
            p.product_size as idSize,
            s.name as size,
            p.price,
            p.stock,
            c.name as category,
            p.description,
            p.state 
            from product p
            INNER JOIN category c
            INNER JOIN design d
            INNER JOIN size s
            on p.product_category = c.id_category 
            AND p.product_design = d.id_design 
            AND p.product_size = s.id_size
            order by p.barcode";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }
        public static function getProductsOfProductBO($idProduct){
            $conecction = new Connection();
            $query = "SELECT
            pr.is_product as barcodePromo,
            p1.name as namePromo,
            p1.stock as stockPromo,
            pr.have_product as barcodeProd,
            pr.quantity,
            p2.id_product as idProduct,
            p2.name as name,
            d.name as design,
            s.name as size,
            p1.state as state
            FROM promo pr, product p1, product p2, design d, size s
            WHERE p1.barcode = pr.is_product 
            AND p2.barcode = pr.have_product
            AND p2.product_design = d.id_design
            AND p2.product_size = s.id_size
            AND p1.id_product ='$idProduct'";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }
        public static function getProductsDisableBO(){
            $conecction = new Connection();
            $query = "SELECT
            p.id_product,
            p.name,
            s.name as size,
            d.name as design,
            p.price,
            p.description,
            p.stock,
            p.state 
            from product p, design d, size s
            where p.state = 0
            AND p.product_design = d.id_design
            AND p.product_size = s.id_size";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }
        public static function getPromosDisableBO(){
            $conecction = new Connection();
            $query = "SELECT
            pr.is_product as CodBarraPROMO,
            p1.name as namePromo,
            p1.stock as stockPromo,
            p1.state as state
            FROM promo pr, product p1
            WHERE p1.barcode = pr.is_product
            AND p1.state = 0";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }
        public static function getOnlyProducts(){
            $conecction = new Connection();
            $query = "SELECT distinct
            p.id_product,
            p.name,
            p.price,
            c.name AS categoryName,
            p.description 
            from product p, category c
            WHERE p.product_category = c.id_category
            AND p.product_category != 1";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }
        public static function getSuggestIdByPromos(){
            $conecction = new Connection();
            $query = "SELECT
            p.id_product
            FROM product p
            WHERE p.product_category = 1
            ORDER BY id_product desc
            LIMIT 1";
            return $conecction->getData($query)->fetch_assoc();
        }

//////////////////////////////OTROS GETS///////////////////////////////////////////////////////
        //Se deberia llamar obtener barcode pasandole la condicion UNIQUE (id Prod , id design , idsize)
        public static function identifyProduct($idProduct,$prodDesign,$prodSize){
            $conecction = new Connection();
            $query = "SELECT p.barcode from product p WHERE p.id_product = $idProduct AND  p.product_design = $prodDesign AND p.product_size =$prodSize";
            return $conecction->getData($query)->fetch_assoc();
        }
        public static function identifyModel($barcode,$prodDesign,$prodSize){
            $conecction = new Connection();
            $query = "SELECT *
            FROM product p 
            WHERE p.barcode = $barcode
            AND p.product_design = $prodDesign
            AND p.product_size = $prodSize";
            return $conecction->getData($query)->fetch_assoc();
        }
        public static function getProductsByNameSize($nameSize){
            $conecction = new Connection();
            $query = "SELECT
            p.barcode,
            p.id_product,
            p.name,
            d.name as design,
            s.name as size,
            p.price,
            p.stock,
            c.name as category,
            p.description,
            p.state 
            FROM product p
            INNER JOIN category c
            INNER JOIN design d
            INNER JOIN size s
            on p.product_category = c.id_category 
            AND p.product_design = d.id_design 
            AND p.product_size = s.id_size
            AND p.state = 1
            AND s.name = '$nameSize'";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }  
        public static function getProductsByNameDesign($nameDesign){
            $conecction = new Connection();
            $query = "SELECT
            p.barcode,
            p.id_product,
            p.name,
            d.name as design,
            s.name as size,
            p.price,
            p.stock,
            c.name as category,
            p.description,
            p.state
            FROM product p
            INNER JOIN category c
            INNER JOIN design d
            INNER JOIN size s
            on p.product_category = c.id_category 
            AND p.product_design = d.id_design 
            AND p.product_size = s.id_size
            AND p.state = 1
            AND d.name = '$nameDesign'";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }
        public static function getAllProductsOfPromoById($idProduct){
            $conecction = new Connection();
            $query = "SELECT
            pr.is_product as barcodePromo,
            p1.name as namePromo,
            p1.stock as stockPromo,
            pr.have_product as barcodeProd,
            pr.quantity,
            p2.id_product as idProduct,
            p2.name as name,
            d.name as design,
            s.name as size
            FROM promo pr, product p1, product p2, design d, size s
            WHERE p1.barcode = pr.is_product 
            AND p2.barcode = pr.have_product
            AND p2.product_design = d.id_design
            AND p2.product_size = s.id_size
            AND p1.id_product ='$idProduct'";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }
        public static function getStateOfProduct($barcode){
            $conecction = new Connection();
            $query = "SELECT state from product where barcode = $barcode";
            return $conecction->getData($query)->fetch_assoc();
        }
        //Validacion para cuando editan un modelo no ingresen uno que ya existe
        public static function identifyProductByBarcode($barcode){
            $conecction = new Connection();
            $query = "SELECT p.id_product
            FROM product p
            WHERE p.barcode = '$barcode'";
            return $conecction->getData($query)->fetch_assoc();
        }
        
        
        //MODIFICACIONES
        public static function updateAttributesOfProduct($barcode, $name, $stock, $price, $description){
            $conecction = new Connection();
            $query = "UPDATE product SET name = '$name', stock = $stock, price = $price, description = '$description' WHERE barcode = $barcode ";
            return $conecction->setData($query);
        }
        public static function updateStockProductsOfPromo($barcode, $unitsNecesary){
            $conecction = new Connection();
            $query = "UPDATE product SET stock = stock - $unitsNecesary WHERE barcode = $barcode ";
            return $conecction->setData($query);
        }
        public static function updateMoreStockProductsOfPromo($barcode, $addUnits){
            $conecction = new Connection();
            $query = "UPDATE product SET stock = stock + $addUnits WHERE barcode = $barcode ";
            return $conecction->setData($query);
        }
        public static function updatePromo($idProduct, $name, $stock, $price, $description){
            $conecction = new Connection();
            $query = "UPDATE product SET name = '$name', stock = $stock, price = $price, description = '$description' WHERE id_product = $idProduct ";
            return $conecction->setData($query);
        }
        public static function updateModel($barcode,$prodDesign,$prodSize,$stock){
            $conecction = new Connection();
            $query = "UPDATE product SET product_design = '$prodDesign', product_size = '$prodSize', stock = '$stock' WHERE barcode = '$barcode' ";
            return $conecction->setData($query);
        }
        public static function updateProductLineAttributes($idProduct, $name,$prodCategory, $price, $description){
            $conecction = new Connection();
            $query = "UPDATE product SET name = '$name', product_category = $prodCategory, price = $price, description = '$description' WHERE id_product = $idProduct ";
            return $conecction->setData($query);
        }            
        public static function disableModel($barcode){
            $conecction = new Connection();
            $query = "UPDATE product SET state = 0 WHERE barcode = $barcode ";
            return $conecction->setData($query);
        }
        public static function disableLineOfProduct($idProduct){
            $conecction = new Connection();
            $query = "UPDATE product SET state = 0 WHERE id_product = $idProduct ";
            return $conecction->setData($query);
        }
        public static function activeModel($barcode){
            $conecction = new Connection();
            $query = "UPDATE product SET state = 1 WHERE barcode = $barcode ";
            return $conecction->setData($query);
        }
        public static function activeLineOfProduct($idProduct){
            $conecction = new Connection();
            $query = "UPDATE product SET state = 1 WHERE id_product = $idProduct ";
            return $conecction->setData($query);
        }
        public static function saveByTransacction($queries){
        $conecction = new Connection();
        $instanceMySql = $conecction->getInstance();
        $instanceMySql->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
        $result_transaccion = true;
        foreach($queries as $key=>$query){
        $resultInsert = $instanceMySql->query($query[$key]);
            if (!$resultInsert) $result_transaccion = false;
            }   
            if($result_transaccion){
                $instanceMySql->commit();
                return true;
            }else{
                $instanceMySql->rollback();
                return false;
            }
        }
        public static function createPromo($idProduct,$name, $stock,$price, $description,$contains){
            $conecction = new Connection();
            $response = new Response();
            $instanceMySql = $conecction->getInstance();
            $instanceMySql->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            $result_transaccion = true;
            $createPromo = "INSERT INTO product (id_product, name, product_category, product_design, product_size, stock, price, description) VALUES ('$idProduct','$name',1,1,1,'$stock','$price', '$description')";
            $resultCreatePromo = $instanceMySql->query($createPromo);
            if(!$resultCreatePromo)  $result_transaccion = false;
            $isProduct = $instanceMySql->insert_id;
            $queries = array();
            $index = 0;
            //FOREACH DE VALIDACIONES
            foreach ($contains as $contain) {
            $haveProduct = $contain['haveProduct'];
            $quantity = $contain['quantity'];
            
            //Valido que exista el producto que se agrega a la promo
            $productExist = ProductModel::getProductByBarcodeBO($haveProduct);
            if (!$productExist) {
                echo ($response->error203("No existe el producto $haveProduct"));
                $instanceMySql->rollback();
                die();   
            }    
            //Valido el estado del producto que se agrega a la promo
            $state = ProductModel::getStateOfProduct($haveProduct);
            if ($state["state"] == 0) {
                echo ($response->error203("El producto $haveProduct no se encuentra activo"));
                $instanceMySql->rollback();
                die();
            }
            //Valido que cantidad a agregar no sea mayor a la cantidad disponible del producto
            $stockExist = ProductModel::getStockProductByBarcode($haveProduct);
            if (($quantity*$stock)>$stockExist["stock"]){
                echo ($response->error203("No dispone de tantas unidades para $haveProduct"));
                $instanceMySql->rollback();
                die();   
            }
            $unitsToSubtract = ($quantity*$stock);
            
            $decreaseStock = ProductModel::updateStockProductsOfPromo($haveProduct,$unitsToSubtract);
            if(!$decreaseStock){
            echo ($response->error203("Hubo un problema al descontar las unidades de $haveProduct"));
            $instanceMySql->rollback();
            die();   
            }
            $query = array($index => "INSERT INTO promo (is_product, have_product, quantity) VALUES ($isProduct,$haveProduct, $quantity)");
            array_push($queries, $query);
            $index++;
        }
            
        
            //FOREACH PARA CREAR LAS QUERYS
            foreach($queries as $key=>$query){
            $resultInsertQuerys = $instanceMySql->query($query[$key]);
                if (!$resultInsertQuerys) $result_transaccion = false;
                }
                if($result_transaccion){
                    $instanceMySql->commit();
                    return true;
                }else{
                    $instanceMySql->rollback();
                    return false;
                }
             }
    }
        
?>
