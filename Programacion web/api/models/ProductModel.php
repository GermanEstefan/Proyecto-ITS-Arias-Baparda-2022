<?php
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
        //Valida que un prod sea unico sin pasarle su PK (barcode)
        public static function identifyProduct($idProduct,$prodDesign,$prodSize){
            $conecction = new Connection();
            $query = "SELECT * from product WHERE id_product = $idProduct and  product_design = $prodDesign and product_size =$prodSize";
            return $conecction->getData($query)->fetch_assoc();
        }
        //Solo se usa para validar cantidad de unidades disponibles para agregar un producto a una promo
        public static function getStockProductByBarcode($barcode){
            $conecction = new Connection();
            $query = "SELECT stock from product WHERE barcode = $barcode";
            return $conecction->getData($query)->fetch_assoc();
        }
        //Solo se usa para obtener el barcode para un idProd PROMO. No sirve para Prod por que Prod tiene MODELOS
        public static function getBarcodeByIdProduct($idProduct){
            $conecction = new Connection();
            $query = "SELECT barcode from product WHERE id_product = $idProduct";
            return $conecction->getData($query);
        }
        //CONSULTAS 
        public static function getProductByBarcode($barcode){
            $conecction = new Connection();
            $query = "SELECT  p.barcode ,p.id_product,p.name, d.name as diseño,s.name as talle,p.price,p.stock,c.name as categoria,p.description,p.state from product p
            inner join category c
            inner join design d
            inner join size s
            on p.product_category = c.id_category 
            and p.product_design = d.id_design 
            and p.product_size = s.id_size  
            and p.barcode = '$barcode'";
            return $conecction->getData($query)->fetch_assoc();
        }
        public static function getProductById($idProduct){
            $conecction = new Connection();
            $query = "SELECT  p.barcode ,p.id_product,p.name, d.name as diseño,s.name as talle,p.price,p.stock,c.name as categoria,p.description,p.state from product p
            inner join category c
            inner join design d
            inner join size s
            on p.product_category = c.id_category 
            and p.product_design = d.id_design 
            and p.product_size = s.id_size  
            and p.id_product = '$idProduct'";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }
        public static function getProductByName($name){
            $conecction = new Connection();
            $query = "SELECT  p.barcode ,p.id_product,p.name, d.name as diseño,s.name as talle,p.price,p.stock,c.name as categoria,p.description,p.state from product p
            inner join category c
            inner join design d
            inner join size s
            on p.product_category = c.id_category 
            and p.product_design = d.id_design 
            and p.product_size = s.id_size  
            and p.name = '$name'";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }
        public static function getAllProducts(){
            $conecction = new Connection();
            $query = "SELECT  p.barcode ,p.id_product,p.name, d.name as diseño,s.name as talle,p.price,p.stock,c.name as categoria,p.description,p.state from product p
            inner join category c
            inner join design d
            inner join size s
            on p.product_category = c.id_category 
            and p.product_design = d.id_design 
            and p.product_size = s.id_size";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }
        public static function getStateOfProduct($barcode){
            $conecction = new Connection();
            $query = "SELECT state from product where barcode = $barcode";
            return $conecction->getData($query)->fetch_assoc();
        }
        public static function getAllProductsActive(){
            $conecction = new Connection();
            $query = "SELECT  p.barcode ,p.id_product,p.name, d.name as diseño,s.name as talle,p.price,p.stock,c.name as categoria,p.description,p.state from product p
            inner join category c
            inner join design d
            inner join size s
            on p.product_category = c.id_category 
            and p.product_design = d.id_design 
            and p.product_size = s.id_size
            and p.state = 1";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }
        public static function getAllProductsDisable(){
            $conecction = new Connection();
            $query = "SELECT  p.barcode ,p.id_product,p.name, d.name as diseño,s.name as talle,p.price,p.stock,c.name as categoria,p.description,p.state from product p
            inner join category c
            inner join design d
            inner join size s
            on p.product_category = c.id_category 
            and p.product_design = d.id_design 
            and p.product_size = s.id_size
            and p.state = 0";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }
        //Sirve UNICAMENTE para que al momento de eliminar una categoria validemos si esta en uso
        public static function getProductsByIdCategory($idCategory){
            $conecction = new Connection();
            $query = "SELECT * from product 
            where product_category = '$idCategory'";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }
        public static function getProductsByNameCategory($nameCategory){
            $conecction = new Connection();
            $query = "SELECT  p.barcode ,p.id_product,p.name, d.name as diseño,s.name as talle,p.price,p.stock,c.name as categoria,p.description,p.state from product p
            inner join category c
            inner join design d
            inner join size s
            on p.product_category = c.id_category 
            and p.product_design = d.id_design 
            and p.product_size = s.id_size
            and p.state = 1
            and c.name = '$nameCategory'";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }
        //Sirve UNICAMENTE para que al momento de eliminar un talle validemos si esta en uso
        public static function getProductsByIdSize($idSize){
            $conecction = new Connection();
            $query = "SELECT * FROM product 
            where product_size = '$idSize'";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }
        public static function getProductsByNameSize($nameSize){
            $conecction = new Connection();
            $query = "SELECT  p.barcode ,p.id_product,p.name, d.name as diseño,s.name as talle,p.price,p.stock,c.name as categoria,p.description,p.state from product p
            inner join category c
            inner join design d
            inner join size s
            on p.product_category = c.id_category 
            and p.product_design = d.id_design 
            and p.product_size = s.id_size
            and p.state = 1
            and s.name = '$nameSize'";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }
        //Sirve UNICAMENTE para que al momento de eliminar un diseño validemos si esta en uso
        public static function getProductsByIdDesign($idDesign){
            $conecction = new Connection();
            $query = "SELECT * from product
            where product_design = '$idDesign'";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }
        public static function getProductsByNameDesign($nameDesign){
            $conecction = new Connection();
            $query = "SELECT  p.barcode ,p.id_product,p.name, d.name as diseño,s.name as talle,p.price,p.stock,c.name as categoria,p.description,p.state from product p
            inner join category c
            inner join design d
            inner join size s
            on p.product_category = c.id_category 
            and p.product_design = d.id_design 
            and p.product_size = s.id_size
            and p.state = 1
            and d.name = '$nameDesign'";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }
        //MODIFICACIONES
        public static function updateAttributesOfProduct($barcode, $name, $stock, $price, $description){
            $conecction = new Connection();
            $query = "UPDATE product SET name = '$name', stock = $stock, price = $price, description = '$description' WHERE barcode = $barcode ";
            return $conecction->setData($query);
        }
        public static function updateProduct($barcode,$idProduct,$name,$prodCategory,$prodDesign,$prodSize,$stock,$price,$description){
            $conecction = new Connection();
            $query = "UPDATE product SET id_product = '$idProduct', name = '$name', product_category = '$prodCategory', product_design = '$prodDesign', product_size = '$prodSize', stock = '$stock', price = '$price', description = '$description' WHERE barcode = '$barcode' ";
            return $conecction->setData($query);
        }
        public static function updateProductLineAttributes($idProduct, $name,$prodCategory, $price, $description){
            $conecction = new Connection();
            $query = "UPDATE product SET name = '$name', product_category = $prodCategory, price = $price, description = '$description' WHERE id_product = $idProduct ";
            return $conecction->setData($query);
        }            
        public static function disableProduct($barcode){
            $conecction = new Connection();
            $query = "UPDATE product SET state = 0 WHERE barcode = $barcode ";
            return $conecction->setData($query);
        }
        public static function disableLineOfProduct($idProduct){
            $conecction = new Connection();
            $query = "UPDATE product SET state = 0 WHERE id_product = $idProduct ";
            return $conecction->setData($query);
        }
        public static function activeProduct($barcode){
            $conecction = new Connection();
            $query = "UPDATE product SET state = 1 WHERE barcode = $barcode ";
            return $conecction->setData($query);
        }
        public static function activeLineOfProduct($idProduct){
            $conecction = new Connection();
            $query = "UPDATE product SET state = 1 WHERE id_product = $idProduct ";
            return $conecction->setData($query);
        }
        public static function createPromo($idProduct,$name, $stock,$price, $description){
            $conecction = new Connection();
            $createPromo = "INSERT INTO product (id_product, name, product_category, product_design, product_size, stock, price, description) VALUES ('$idProduct','$name',1,1,1,'$stock','$price', '$description')";
            return $conecction->setData($createPromo);
            if(!$createPromo){
                return false;
            }
            return true;
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
        public static function ProductsOfPromoTransacction($queries){
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
    }
        
?>
