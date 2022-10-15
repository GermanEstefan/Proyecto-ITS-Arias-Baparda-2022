<?php

include_once('./helpers/Response.php');
include_once('./helpers/Token.php');
include_once("./models/SupplyModel.php");
include_once("./models/SupplyDetailModel.php");
include_once("./models/SupplierModel.php");
include_once("./models/ProductModel.php");
include_once("./models/EmployeeModel.php");

class SupplyController
{
    private $response;
    private $jwt;

    function __construct()
    {
        $this->response = new Response();
        $this->jwt = new Token();
    }

    //Validar los campos generales
    private function validateBodyOfSupply($supplyData)
    {
        if (
            !isset($supplyData['supplier_id'])
            || !isset($supplyData['employee_id'])
            || !isset($supplyData['comment'])
        ) return false;
        return $supplyData;
    }

    private function validateBodyOfDetail($supplyData)
    {
        if (!isset($supplyData['products'])) return false;
        return $supplyData;
    }

    private function validateExistingSupplier($supplyData)
    {
        $id = $supplyData['supplier_id'];
        $supplier = SupplierModel::activeSupplier($id);
        if (!$supplier) {
            return false;
        }
        return true;
    }

    private function validateEmployee($supplyData)
    {
        $id = $supplyData['employee_id'];
        $employee = EmployeeModel::getEmployeeById($id);
        if (!$employee) {
            return false;
        }
        return true;
    }

    private function validateProduct($barcode)
    {
        $product = ProductModel::getStateOfProduct($barcode);
        if (!$product) {
            return false;
        }
        return true;
    }

    //Ingresar registro de compra a proveedor (supply)
    public function saveSupply($supplyData)
    {
        $this->jwt->verifyTokenAndGetIdUserFromRequest();
        $bodySupplyValid = $this->validateBodyOfSupply($supplyData);
        $supplierIsValid = $this->validateExistingSupplier($supplyData);
        $employeeValid = $this->validateEmployee($supplyData);
        $bodyDetailValid = $this->validateBodyOfDetail($supplyData);

        //validamos los datos del supply
        if ((!$bodySupplyValid) || (!$supplierIsValid) || (!$employeeValid)) {
            echo $this->response->error203("Error al querer ingresar el supply");
            die();
        }
        $supplierId = $supplyData['supplier_id'];
        $employeeId = $supplyData['employee_id'];
        $comment = $supplyData['comment'];

        if (!$bodyDetailValid) {
            echo $this->response->error203("Error al querer ingresar el detalle");
            die();
        }
        $detailItems = $supplyData['products'];

        //validamos los datos que iran en el detalle
        foreach ($detailItems as $product) {
            $productValid = $this->validateProduct($product['barcode']);
            if (!$productValid) {
                echo $this->response->error203("Algun producto ingresado no es valido");
                die();
            }
        }

        //validar que no se repita el mismo codigo de barras
        $barcodes = array();
        foreach ($detailItems as $product) {
            array_push($barcodes, $product['barcode']);
        }
        if (count(array_unique($barcodes)) < count($barcodes)) {
            echo $this->response->error203("El producto esta repetido");
            die();
        }
        //Ingresamos los datos generales del supply
        $supply = new SupplyModel($supplierId, $employeeId, $comment);
        $result = $supply->save();
        if (!$result) {
            echo $this->response->error203("error al insertar");
            die();
        }
        echo $this->response->successfully("supply ingresado");

        //Obtenemos el ultimo ID ingresado
        $lastID = SupplyModel::getLastID()[0];

        //Inicializamos el array de los productos que iran en el detalle
        $queries = array();
        $index = 0;

        //Ingresamos los datos del detalle
        foreach ($detailItems as $product) {
            $productValid = $this->validateProduct($product['barcode']);
            if (!$productValid) {
                echo $this->response->error203("Error, el producto no es valido");
                $delete = SupplyModel::deleteLastInsertSupply($lastID);
                if (!$delete) {
                    echo $this->response->error203("Error al borrar el ultimo registro incompleto");
                }
                echo $this->response->error203("hubo un error en los datos del detalle, se borrara el ultimo registro incompleto");
                die();
            }
            $barcode = $product['barcode'];
            $quantity = $product['quantity'];
            $costo = $product['costo'];

            $query = array($index => "INSERT INTO supply_detail (supply_id, barcode_id, quantity, cost_unit) VALUES ('$lastID', '$barcode', '$quantity', '$costo')");
            array_push($queries, $query);
            $index++;
        }

        $result = SupplyModel::saveByTransacction($queries);
        if (!$result) {
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Detalle del supply ingresado");
    }

    //CONSULTAS
    //todos los registros de compras
    public function getAllSupply()
    {
        $supply = SupplyModel::getAllSupply();
        echo $this->response->successfully("Registros del sistema: ", $supply);
        die();
    }

    //todos los registros con detalle
    public function getAllSupplyWithDetail()
    {
        $supply = SupplyModel::getAllSupplyWithDetail();
        echo $this->response->successfully("Registros del sistema: ", $supply);
        die();
    }

    //detalle por id de supply
    public function getDetailById($id)
    {
        $supply = SupplyModel::getDetailById($id);
        if (!$supply) {
            echo $this->response->error400("No hay registro disponible");
            die();
        }
        echo $this->response->successfully("Registros del sistema: ", $supply);
        die();
    }

    //registros con detalle por ID
    public function getSupplyById($id)
    {
        $supply = SupplyModel::getSupplyById($id);
        if (!$supply) {
            echo $this->response->error400("No hay registro disponible");
            die();
        }
        echo $this->response->successfully("Registro: ", $supply);
        die();
    }

    //registros por empleado
    public function getSupplyMadeByEmployee($id)
    {
        $supply = SupplyModel::getSupplyMadeByEmployee($id);
        if (!$supply) {
            echo $this->response->error400("No hay registro disponible");
            die();
        }
        echo $this->response->successfully("Registro: ", $supply);
        die();
    }

    //Registro por producto
    public function getSupplyByProductId($id)
    {
        $supply = SupplyModel::getSupplyByProductId($id);
        if (!$supply) {
            echo $this->response->error400("No hay registro disponible");
            die();
        }
        echo $this->response->successfully("Registro: ", $supply);
        die();
    }

    //Registro por proveedor
    public function getSupplyBySupplierId($id)
    {
        $supply = SupplyModel::getSupplyBySupplierId($id);
        if (!$supply) {
            echo $this->response->error400("No hay registro disponible");
            die();
        }
        echo $this->response->successfully("Registro: ", $supply);
        die();
    }
}
