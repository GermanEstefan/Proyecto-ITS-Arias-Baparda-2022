<?php
    require_once("./helpers/Response.php");
    require_once("./database/Connection.php");
    require_once("./models/ProductModel.php");
    class SaleModel extends Connection {

        private $address;
        private $idClient;
        private $delivery;
        private $payment;

        function __construct($address,$idClient,$delivery,$payment){
            $this->address = $address;
            $this->idClient = $idClient;
            $this->delivery = $delivery;
            $this->payment = $payment;
            parent::__construct();
        }
        //CONSULTAS
        public static function getSaleById($idSale){
            $conecction = new Connection();
            $query = "SELECT
            s.id_sale,
            date_format(s.date, '%d/%m/%Y %T') AS saleDate,
            st.name AS statusActual,
            s.address,
            d.name AS delivery,
            s.user_purchase AS idUser,
            c.company_name AS razonSocial,
            c.rut_nr AS rut,
            u.name AS name,
            u.surname AS lastname,
            s.payment,
            s.total
            FROM sale s, customer c, user u,report r, status st, delivery_time d
            WHERE s.id_sale = '$idSale'
            AND c.customer_user = s.user_purchase
            AND u.id_user = s.user_purchase
            AND s.id_sale = r.sale_report
            AND r.status_report = st.id_status
            AND s.sale_delivery = d.id_delivery";
            return $conecction->getData($query)->fetch_assoc();
        }
        public static function getDetailSaleById($idSale){
            $conecction = new Connection();
            $query = "SELECT 
            sd.sale_id,
            p.name as product,
            sd.product_sale as barcode, 
            sd.quantity,
            sd.total,
            s.total AS totalSale
            FROM sale_detail sd, product p, sale s 
            WHERE sale_id = '$idSale'
            AND sd.product_sale = p.barcode
            AND sd.sale_id = s.id_sale";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }
        public static function getSalesByStatus($status){
            $conecction = new Connection();
            $query = "SELECT 
            r.sale_report AS idSale,
            s.name AS nameStatus,
            r.employee_report AS docEmployee,
            date_format(r.date, '%d/%m/%Y %T') AS lastUpdate,
            r.comment AS lastComment
            FROM report r , status s
            WHERE r.status_report = s.id_status
            AND s.name LIKE '$status'";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
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
            $saleInsert = "INSERT INTO sale (address, user_purchase, sale_delivery, payment) VALUES ('$this->address', '$this->idClient','$this->delivery', '$this->payment')";
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

            $productExist = ProductModel::getProductByBarcodeBO($barcode);
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
            if($quantity<1){
                echo ($response->error203("Error en las unidades indicadas para el producto $barcode"));
                $instanceMySql->rollback();
                die();
            }
            
                        
            $decreaseStock = ProductModel::updateStockProductsOfPromo($barcode,$quantity);
            if(!$decreaseStock){
            echo ($response->error203("Hubo un problema al descontar las unidades de $barcode"));
            $instanceMySql->rollback();
            die();   
            }
            $query = array($index => "INSERT INTO sale_detail (sale_id, product_sale, quantity) VALUES ($idSale,$barcode, $quantity)");
            array_push($queries, $query);
            $index++;
                
        }
       
        foreach($queries as $key=>$query){
        $resultInsertQuerys = $instanceMySql->query($query[$key]);
            if (!$resultInsertQuerys){
                echo ($response->error203("Hubo un error para el detalle de la venta. Contacte al soporte tecnico"));
                $instanceMySql->rollback();
                die();
            }
        }
        $payment = $this->payment;
            //ALTA EN REPORTES
            if($payment == 0){
            //el pago es en efectivo, queda pendiente de confirmacion
            $firstReportForSalePending = "INSERT INTO report (sale_report, status_report, employee_report, comment) VALUES ($idSale, 2, 1, 'Respuesta automatica')";
            $generateReportForSale = $instanceMySql->query($firstReportForSalePending);
            if(!$generateReportForSale) $result_transaccion = false;
            if($result_transaccion){
                    $instanceMySql->commit();
                    return true;
                }else{
                    $instanceMySql->rollback();
                    return false;
                }
            }
            if($payment == 1){
                //el pago ya fue confirmado por algun medio electronico, queda Confirmado
                $firstReportForSaleConfirmed = "INSERT INTO report (sale_report, status_report, employee_report, comment) VALUES ($idSale, 3, 1, 'Respuesta automatica')";
                $generateReportForSale = $instanceMySql->query($firstReportForSaleConfirmed);
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
}
?>