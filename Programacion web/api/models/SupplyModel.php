<?php
require_once("./database/Connection.php");
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

    //verficia que el producto exista y este activo
    public static function getProductActive($id)
    {
        $conecction = new Connection();
        $query = "SELECT barcode, state FROM product WHERE barcode = $id AND state = '1'";
        return $conecction->getData($query)->fetch_assoc();
    }

    //verifica que halla stock del producto por id
    public static function getStock($id)
    {
        $conecction = new Connection();
        $query = "SELECT barcode, stock FROM product WHERE barcode = $id AND stock > '0'";
        return $conecction->getData($query)->fetch_assoc();
    }

    //sacar ultimo ID Ingresado
    public static function getLastID()
    {
        $conecction = new Connection();
        $query = "SELECT id_supply FROM supply ORDER BY id_supply DESC LIMIT 1";
        return $conecction->getData($query)->fetch_array();
    }

    //sacar todos los supply
    public static function getAllSupply()
    {
        $conecction = new Connection();
        $query = "SELECT * FROM supply";
        return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
    }

    //sacar todos los supply con sus respectivos detalles
    public static function getAllSupplyWithDetail()
    {
        $conecction = new Connection();
        $query = "SELECT * FROM supply s
                  INNER JOIN supply_detail sd on s.id_supply = sd.supply_id";
        return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
    }

    //sacar detalle por id de supply
    public static function getDetailById($id)
    {
        $conecction = new Connection();
        $query = "SELECT * FROM supply_detail WHERE supply_id = $id";
        return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
    }

    //Sacar supply por fecha mayor a.. 
    public static function getSupplyFromDate($date)
    {
        $conecction = new Connection();
        $query = "SELECT * FROM supply WHERE date >= '$date'";
        return $conecction->getData($query)->fetch_all();
    }

    //sacar supply por empleado
    public static function getSupplyMadeByEmployee($employee_id)
    {
        $conecction = new Connection();
        $query = "SELECT * FROM supply WHERE employee_ci = $employee_id";
        return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
    }

    //sacar supply por id
    public static function getSupplyById($id)
    {
        $conecction = new Connection();
        $query = "SELECT * FROM supply WHERE id_supply = $id";
        return $conecction->getData($query)->fetch_assoc();
    }

    //sacar registro por id de producto
    public static function getSupplyByProductId($id)
    {
        $conecction = new Connection();
        $query = "SELECT * FROM supply s
                  INNER JOIN supply_detail sd on sd.supply_id = s.id_supply 
                  AND sd.barcode_id = $id";
        return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
    }

    //sacar registro por id de proveedor
    public static function getSupplyBySupplierId($id)
    {
        $conecction = new Connection();
        $query = "SELECT * FROM supply s
                  INNER JOIN supply_detail sd on sd.supply_id = s.id_supply 
                  AND s.supplier_id = $id";
        return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
    }

    //ingresar un registro de supply
    public function save()
    {
        $conecction = new Connection();
        $query = "INSERT INTO supply (date, supplier_id, employee_ci, comment) VALUES (CURDATE(), $this->supplier_id, $this->employee_ci, '$this->comment');";
        $result = $conecction->setData($query);
        if (!$result) {
            return false;
        }
        return true;
    }

    //ingresar el detalle
    public static function saveByTransacction($queries)
    {
        $conecction = new Connection();
        $instanceMySql = $conecction->getInstance();
        $instanceMySql->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
        $result_transaccion = true;
        foreach ($queries as $key => $query) {
            $resultInsert = $instanceMySql->query($query[$key]);
            if (!$resultInsert) $result_transaccion = false;
        }
        if ($result_transaccion) {
            $instanceMySql->commit();
            return true;
        } else {
            $instanceMySql->rollback();
            return false;
        }
    }

    //borrar ultimo ingreso en caso de error en el detalle
    public static function deleteLastInsertSupply($id){
        $conecction = new Connection();
        $query = "DELETE FROM supply WHERE id_supply = $id";
        $result = $conecction->setData($query);
        if (!$result) {
            return false;
        }
        return true;
    }
}
