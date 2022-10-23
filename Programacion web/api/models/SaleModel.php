<?php
    require_once("./helpers/Response.php");
    require_once("./database/Connection.php");
    require_once("./ProductModel.php");
    class SaleModel extends Connection {

        private $address;
        private $idClient;
        private $delivery;
        private $payment;

        function __construct($address,$idClient,$delivery,$payment){
            $this->name = $address;
            $this->description = $idClient;
            $this->description = $delivery;
            $this->description = $payment;
            parent::__construct();
        }
        //CONSULTAS
        public static function getSaleById($id){
            $conecction = new Connection();
            $query = "SELECT * from sale WHERE id_sale='$id'";
            return $conecction->getData($query)->fetch_assoc();
        }
        public static function getAllSales(){
            $conecction = new Connection();
            $query = "SELECT * from sale";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }
        //CONSULTAS POR FECHA
        public static function getAllSalesForThisDate($date){
            $conecction = new Connection();
            $query = "SELECT * from sale WHERE date LIKE '$date%'";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }
        public static function getSalesByDate($date){
            $conecction = new Connection();
            $query = "SELECT * from sale where date = '$date'";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }
        public static function getSalesByClient($client){
            $conecction = new Connection();
            $query = "SELECT * from sale WHERE mail = $";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }
        public static function getSalesByDelivery($delivery){
            $conecction = new Connection();
            $query = "SELECT * from sale where sale_delivery = $delivery";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }
        public static function GetTotalSalesIncome(){
            $conecction = new Connection();
            $query = "SELECT SUM(total) from sale";
            return $conecction->getData($query)->fetch_assoc();
        }
        public static function getInfoCustomerByEmail($mailClient){
            $conecction = new Connection();
            $query = "SELECT u.id_user,
            c.company_name AS 'Razon Social',
            c.rut_nr AS Rut,
            u.name as 'Nombre',
            u.surname as 'Apellido',
            u.phone as Contacto
            FROM user u, customer c
            WHERE c.customer_user = u.id_user
            AND u.email = '$mailClient'";
            return $conecction->getData($query)->fetch_assoc();
        }
        public static function getIdCustomerByEmail($mailClient){
            $conecction = new Connection();
            $query = "SELECT u.id_user
            FROM user u, customer c
            WHERE c.customer_user = u.id_user
            AND u.email = '$mailClient'";
            return $conecction->getData($query)->fetch_assoc();
        }
                
        public function saveSale($productsForSale){
            $response = new Response();
            $conecction = new Connection();
            $instanceMySql = $conecction->getInstance();
            $instanceMySql->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            $result_transaccion = true;
            $saleInsert = "INSERT INTO sale (address, user_purchase, sale_delivery, payment) VALUES ('$this->address', '$this->idClient','$this->delivery', $this->payment)";
            $resultCreateSale = $instanceMySql->query($saleInsert);
            if(!$resultCreateSale)  $result_transaccion = false;
            $idSale = $instanceMySql->insert_id;
            //INICIO SALE_DETAIL Array de productos
            $queries = array();
            $index = 0;
            //FOREACH DE VALIDACIONES
            foreach ($productsForSale as $product) {
                $barcode = $product['barcode'];
                $quantity = $product['quantity'];
                //Validaciones
            $productExist = ProductModel::getAllProductByBarcode($barcode);
            if (!$productExist) {
                echo ($response->error203("No existe el producto $barcode"));
                $instanceMySql->rollback();
                die();   
            }    
            $state = ProductModel::getStateOfProduct($barcode);
            if ($state["state"] == 0) {
                echo ($response->error203("El producto $barcode no se encuentra activo"));
                $instanceMySql->rollback();
                die();
                }
            $stockExist = ProductModel::getStockProductByBarcode($barcode);
            if (($quantity)>$stockExist["stock"]){
                echo ($response->error203("No dispone de tantas unidades para $barcode"));
                $instanceMySql->rollback();
                die();   
                }
            }
            $query = array($index => "INSERT INTO sale_detail (id_sale, product_sale, quantity) VALUES ($idSale,$barcode, $quantity)");
            array_push($queries, $query);
            $index++;
            foreach($queries as $key=>$query){
            $resultInsertQuerys = $instanceMySql->query($query[$key]);
                if (!$resultInsertQuerys){
                    echo ($response->error203("Hubo un error para el detalle de la venta. Contacte al soporte tecnico"));
                    $instanceMySql->rollback();
                    die(); 
                }
            }

            //ALTA EN REPORTES
            //Hardcodeamos el STATUS 1 PARA PENDIENTES y EMPLOYEE 1 POR QUE ES EL SISTEMA
            $firstReportForSale = "INSERT INTO report (sale_report, status_report, employee_report, comment) VALUES ($idSale, 1, 1, 'Pedido recibido, Venta pendiente')";
            $generateReportForSale = $instanceMySql->query($firstReportForSale);
            if(!$generateReportForSale) $result_transaccion = false;
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