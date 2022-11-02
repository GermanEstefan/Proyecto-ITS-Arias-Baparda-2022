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
            s.id_sale as idSale,
            date_format(s.date, '%d/%m/%Y %T') AS saleDate,
            st.name AS statusActual,
            s.address as addressSale,
            dt.name AS deliverySale,
            u.email AS clientMail,
            c.company_name AS nameCompany,
			concat_ws(' ', u.name , u.surname) AS clientName,
            s.payment,
            sd.product_sale as barcode, 
			concat_ws(' ', p.name, d.name , sz.name) AS productName,
            sd.quantity,
            sd.total,
            s.total AS totalSale
            FROM sale s, customer c,report r, status st, delivery_time dt, sale_detail sd, product p, design d, size sz, user u
            WHERE s.id_sale = $idSale
            AND c.customer_user = s.user_purchase
            AND u.id_user = s.user_purchase
            AND s.id_sale = r.sale_report
            AND r.status_report = st.id_status
            AND s.sale_delivery = dt.id_delivery
            AND sd.product_sale = p.barcode
            AND sd.sale_id = s.id_sale
            AND sz.id_size = p.product_size
            AND d.id_design = p.product_design";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }
        public static function getSalesByStatus($status){
            $conecction = new Connection();
            $query = "SELECT 
            r.sale_report AS idSale,
            s.name AS nameStatus,
            r.employee_report AS docEmployee,
            u.email AS employeeMail,
            sl.total AS totalSale,
            date_format(r.date, '%d/%m/%Y %T') AS lastUpdate
            FROM report r , status s, employee e, user u, sale sl
            WHERE s.name LIKE '$status'
            AND r.status_report = s.id_status
            AND r.employee_report = e.ci
            AND e.employee_user = u.id_user
            AND sl.id_sale = r.sale_report";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }
        public static function getSalesForUserID($idClient){
            $conecction = new Connection();
            $query = "SELECT 
            s.id_sale as ID,
            date_format(s.date, '%d/%m/%Y %H:%mHs') as date, 
            s.total,
            st.name as status   
            from sale s, status st, report r 
            where s.user_purchase = '$idClient'
            AND st.id_status = r.status_report
            AND r.sale_report = s.id_sale
            order by id desc";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }
        public static function getAllSalesByDay($day){
            $conecction = new Connection();
            $query = "SELECT
            s.id_sale as ID,
            date_format(s.date, '%d/%m/%Y %T') as date,
            s.address,
            s.user_purchase as clientID,
            c.company_name AS companyName,
            c.rut_nr AS companyRut,
            concat_ws(' ', u.name , u.surname) AS clientInfo,
            d.name as delivery,
            s.payment,
            s.total
            FROM sale s, delivery_time d, user u, customer c  
            WHERE DATE like '$day%'
            AND s.user_purchase = u.id_user
            AND s.user_purchase = c.customer_user
            AND s.sale_delivery = d.id_delivery";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }
    
        public static function getCustomerAddressToSuggest($email){
            $conecction = new Connection();
            $query = "SELECT u.address
            FROM user u
            WHERE u.email = '$email'";
            return $conecction->getData($query)->fetch_assoc();
        }
        public static function getReportHistoryBySale($idSale){
            $conecction = new Connection();
            $query = "SELECT
            rh.sale_report as saleID,
            rh.idReg AS 'regNr',
            rh.Type AS 'typeReg',
            s.name AS status,
            rh.employee_report AS employeeDoc,
            concat_ws(' ', u.name , u.surname) AS employeeName,
            date_format(rh.date, '%d/%m/%Y %T') AS date,
            rh.comment AS comment
            FROM reportHistory rh, status s,employee e, user u
            WHERE sale_report = $idSale
            AND rh.status_report = s.id_status
            AND rh.employee_report = e.ci
            AND e.employee_user = u.id_user
            ORDER BY Date desc";
            return $conecction->getData($query)->fetch_ALL(MYSQLI_ASSOC);
        }
        public static function getReportHistoryForClient($idSale){
            $conecction = new Connection();
            $query = "SELECT
            rh.sale_report as ID,
            s.name AS status,
            u.name AS personalName,
            date_format(rh.date, '%d/%m/%Y %T') AS date,
            rh.comment AS comment
            FROM reportHistory rh, status s,employee e, user u
            WHERE sale_report = $idSale
            AND rh.status_report = s.id_status
            AND rh.employee_report = e.ci
            AND e.employee_user = u.id_user
            ORDER BY rh.idReg desc";
            return $conecction->getData($query)->fetch_ALL(MYSQLI_ASSOC);
        }
        public static function getSalesByIdDelivery($idDelivery){
            $conecction = new Connection();
            $query = "SELECT 
            d.name AS delivery,
            s.id_sale AS saleID,
            date_format(s.date, '%d/%m/%Y %T') AS saleDate,
            date_format(r.date, '%d/%m/%Y %T') AS lastUpdate,
            concat_ws(' ', u.name , u.surname) AS client,
            s.address,
            u.phone
            FROM sale s, status st, delivery_time d,user u, report r
            WHERE sale_delivery = $idDelivery
            AND st.name = 'confirmado'
            AND s.sale_delivery = d.id_delivery
            AND s.id_sale = r.sale_report
            AND s.user_purchase = u.id_user ";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }

        //update
        public static function updateReportOfSale($idSale,$status,$employeeDoc,$comment){
            $conecction = new Connection();
            $query = "UPDATE report 
            SET status_report = $status,
            employee_report = $employeeDoc,
            comment = '$comment'
            WHERE sale_report = $idSale";
            return $conecction->setData($query);
        }























//ESTO NO ESTA CHEQUEADO NI CONTEMPLADO
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
        public static function getSalesByClient($client){
            $conecction = new Connection();
            $query = "SELECT * from sale WHERE mail = $client";
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
        public static function getInfoClientForMail($idClient){
            $conecction = new Connection();
            $query = "SELECT u.email,
            c.company_name AS 'company',
            u.name as 'Nombre',
            u.surname as 'Apellido'
            FROM user u, customer c
            WHERE c.customer_user = u.id_user
            AND u.id_user = 5004";
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
            $firstReportForSalePending = "INSERT INTO report (sale_report, status_report, employee_report, comment) VALUES ($idSale, 2, 1, 'Respuesta Automatica: Pedido pendiente de pago')";
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
                $firstReportForSaleConfirmed = "INSERT INTO report (sale_report, status_report, employee_report, comment) VALUES ($idSale, 3, 1, 'Respuesta Automatica: Venta Confirmada')";
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