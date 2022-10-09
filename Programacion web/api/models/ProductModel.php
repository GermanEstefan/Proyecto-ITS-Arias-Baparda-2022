<?php
    require_once("./database/Connection.php");
    class ProductModel extends Connection {

        private $idProduct;
        private $name;
        private $prodCategory;
        private $prodDesign;
        private $prodSize;
        private $stock;
        private $price;
        private $description;

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

        public static function identifyProduct($idProduct,$prodCategory,$prodDesign,$prodSize){
            $conecction = new Connection();
            $query = "SELECT * from product WHERE id_product = $idProduct and product_category = $prodCategory and  product_design = $prodDesign and product_size =$prodSize";
            return $conecction->getData($query)->fetch_assoc();
        }

        public static function getProductByBarcode($barcode){
            $conecction = new Connection();
            $query = "SELECT  p.barcode ,p.id_product,p.name, d.name as color,s.name as talle,p.price,p.stock,c.name as categoria,p.description,p.state from product p
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
            $query = "SELECT  p.barcode ,p.id_product,p.name, d.name as color,s.name as talle,p.price,p.stock,c.name as categoria,p.description,p.state from product p
            inner join category c
            inner join design d
            inner join size s
            on p.product_category = c.id_category 
            and p.product_design = d.id_design 
            and p.product_size = s.id_size  
            and p.id_product = '$idProduct'";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }
        public static function getAllProducts(){
            $conecction = new Connection();
            $query = "SELECT  p.barcode ,p.id_product,p.name, d.name as color,s.name as talle,p.price,p.stock,c.name as categoria,p.description,p.state from product p
            inner join category c
            inner join design d
            inner join size s
            on p.product_category = c.id_category 
            and p.product_design = d.id_design 
            and p.product_size = s.id_size";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }
        public static function updateProduct($id, $name, $description){
            $conecction = new Connection();
            $query = "UPDATE product SET name = '$name', description = '$description' WHERE id_product = $id ";
            return $conecction->setData($query);
        }
                
        public function save(){
            $productInsert = "INSERT INTO product (id_product, name, product_category, product_design, product_size, stock, price, description) VALUES ('$this->idProduct','$this->name','$this->prodCategory','$this->prodDesign','$this->prodSize','$this->stock','$this->price', '$this->description')";
            $result = parent::setData($productInsert);
            if(!$result){
                return false;
            }
            return true;
            
        }
    }
        
?>