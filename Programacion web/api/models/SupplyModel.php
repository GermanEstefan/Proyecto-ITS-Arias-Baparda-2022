<?php
require_once("./database/Connection.php");
require_once("./database/Connection.php");
require_once("./models/ProductModel.php");
class SupplyModel extends Connection
{

    private $supplier_id;
    private $employee_ci;
    private $comment;

    function __construct($supplier_id, $employee_ci, $comment)
    {
        $this->supplier_id = $supplier_id;
        $this->employee_ci = $employee_ci;
        $this->comment = $comment;
        parent::__construct();
    }

    
    //CONSULTAS
    public static function getSupplyById($idSupply)
    {
        $conecction = new Connection();
        $query = "SELECT 
        s.id_supply AS idSupply,
        date_format(s.date, '%d/%m/%Y %T') AS date,
        s.supplier_id AS idSupplier,
        sp.company_name AS name,
        sp.rut AS rut,
        s.employee_ci AS employeeDoc,
        concat_ws(' ', u.name , u.surname) AS employeeName,
        s.comment,
        s.total
        FROM supply s, supplier sp, employee e, user u
        WHERE s.id_supply = $idSupply
        AND sp.id_supplier = s.supplier_id
        AND s.employee_ci = e.ci
        AND e.employee_user = u.id_user";
        return $conecction->getData($query)->fetch_assoc();
    }
    public static function getSupplyDetailById($idSupply){
        $conecction = new Connection();
        $query = "SELECT 
        sd.supply_id AS idSupply,
        sd.barcode_id AS barcode,
        p.name AS nameProduct,
        sd.quantity AS quantity,
        sd.cost_unit AS costUnit,
        sd.amount_total AS costTotal,
        s.total AS totalSupply
        FROM supply_detail sd, product p, supply s 
        WHERE supply_id = $idSupply
        AND p.barcode = sd.barcode_id
        AND s.id_supply = sd.supply_id";
        return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
    }
    public static function getAllSupplysByDay($day){
        $conecction = new Connection();
        $query = "SELECT 
        s.id_supply AS idSupply,
        date_format(s.date, '%d/%m/%Y %T') as date,
        e.employee_user AS idEmployee,
        s.employee_ci AS ciEmployee,
        concat_ws(' ', u.name , u.surname) AS employeeName, 
        s.total AS totalSupply
        FROM supply s, employee e, user u
        WHERE s.date LIKE '$day%'
        AND e.ci = s.employee_ci
        AND e.employee_user = u.id_user";
        return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
    }
    public static function getAllSupplys(){
        $conecction = new Connection();
        $query = "SELECT 
        s.id_supply AS idSupply,
        date_format(s.date, '%d/%m/%Y %T') AS date,
        s.supplier_id AS idSupplier,
        sp.company_name AS nameSupplier,
        e.employee_user AS idEmployee,
        s.employee_ci AS ciEmployee,
        concat_ws(' ', u.name , u.surname) AS employeeName, 
        s.total AS totalSupply
        FROM supply s, employee e, user u, supplier sp 
        where e.ci = s.employee_ci
        AND e.employee_user = u.id_user
        AND sp.id_supplier = s.supplier_id";
        return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
    }
    //crear compra
    public function saveSupply($productsForSupply){
        $response = new Response();
        $conecction = new Connection();
        $instanceMySql = $conecction->getInstance();
        $instanceMySql->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
        $result_transaccion = true;
        $supplyInsert = "INSERT INTO supply (supplier_id, employee_ci, comment) VALUES ('$this->supplier_id', '$this->employee_ci','$this->comment')";
        $resultCreateSupply = $instanceMySql->query($supplyInsert);
        if(!$resultCreateSupply)  $result_transaccion = false;
        $idSupply = $instanceMySql->insert_id;
        //INICIO SALE_DETAIL Array de productos
        $queries = array();
        $index = 0;
        //FOREACH DE VALIDACIONES
        foreach ($productsForSupply as $product) {
            $barcode = $product['barcode'];
            $quantity = $product['quantity'];
            $cost_unit = $product['cost_unit'];

            $productExist = ProductModel::getProductByBarcodeBO($barcode);
            if (!$productExist) {
                echo ($response->error203("No existe el producto $barcode"));
                $instanceMySql->rollback();
                die();   
            }
            if($quantity<1){
                echo ($response->error203("Error en las unidades indicadas para el producto $barcode"));
                $instanceMySql->rollback();
                die();
            }            
            $increaseStock = ProductModel::updateMoreStockProductsOfPromo($barcode,$quantity);
            if(!$increaseStock){
                echo ($response->error203("Hubo un problema al agregar las unidades de $barcode"));
                $instanceMySql->rollback();
                die();   
            }
            $query = array($index => "INSERT INTO supply_detail (supply_id, barcode_id, quantity, cost_unit) VALUES ($idSupply,$barcode, $quantity, $cost_unit)");
            array_push($queries, $query);
            $index++;
        }
   
        foreach($queries as $key=>$query){
            $resultInsertQuerys = $instanceMySql->query($query[$key]);
            if (!$resultInsertQuerys){
                echo ($response->error203("Hubo un error para el detalle de compra. Contacte al soporte tecnico"));
                $instanceMySql->rollback();
                die();
            }
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
