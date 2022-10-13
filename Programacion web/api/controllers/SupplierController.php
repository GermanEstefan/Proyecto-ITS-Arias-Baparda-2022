<?php

use LDAP\Result;
use Spipu\Html2Pdf\Tag\Html\Sup;

include_once('./helpers/Response.php');
include_once('./helpers/Token.php');
include_once("./models/SupplierModel.php");

class SupplierController
{

    private $response;
    private $jwt;

    function __construct()
    {
        $this->response = new Response();
        $this->jwt = new Token();
    }

    //validar 
    private function validateBodyOfSupplier($supplierData)
    {
        if (
            !isset($supplierData['rut'])
            || !is_int($supplierData['rut'])
            || !isset($supplierData['company'])
            || !isset($supplierData['phone'])
            || !is_int($supplierData['phone'])
            || !isset($supplierData['address'])
        ) return false;

        return $supplierData;
    }

    //Alta de proveedor
    public function saveSupplier($supplierData){
        $this->jwt->verifyTokenAndGetIdUserFromRequest();
        $bodyIsValid = $this->validateBodyOfSupplier($supplierData);
        if (!$bodyIsValid) {
            echo $this->response->error400("Error en los datos enviados");
            die();
        }
        $rut = $supplierData['rut'];
        $company = $supplierData['company'];
        $phone = $supplierData['phone'];
        $address = $supplierData['address'];

        //Validamos que el rut y el nombre no exista en la DB || O que el proveedor exista pero este dado de baja
        $rutExist = SupplierModel::getSupplierByRut($rut);
        $supplierIsDisabled = SupplierModel::getDisabledSupplierByRut($rut);
        if ($rutExist || $supplierIsDisabled) {
            if ($supplierIsDisabled){
                echo $this->response->error203("El proveedor que quiere ingresar ya existe en el sistema, fue dado de baja");
                die();        
            }
            if ($rutExist){
                echo $this->response->error203("El proveedor que quiere ingresar ya existe en el sistema");
                die();
            }
        }
        
        //insert del proveedor
        $supplier = new SupplierModel($rut, $company, $address, $phone);
        $result = $supplier->save();
        if (!$result){
            echo $this->response->error500("No se pudo realizar el insert");
            die();
        }
        echo $this->response->successfully("Proveedor ingresado con exito");
    }

    //Habilitar un proveedor que halla sido dado de baja
    public function enableSupplier($supplierData){
        $this->jwt->verifyTokenAndGetIdUserFromRequest();
        $bodyIsValid = $this->validateBodyOfSupplier($supplierData);
        if (!$bodyIsValid){
            echo $this->response->error400("Error al enviar los datos");
            die();
        }
        $rut = $supplierData['rut'];

        //validamos que el proveedor exista y este dado de baja
        $supplierToEnable = SupplierModel::getDisabledSupplierByRut($rut);
        if (!$supplierToEnable){
            echo $this->response->error203("El proveedor a habilitar no existe");
            die();
        }

        //Damos de baja el proveedor
        $result = SupplierModel::enableSupplier($rut);
        if (!$result){
            echo $this->response->error400("No se pudo habilitar el proveedor");
            die();
        }
        echo $this->response->successfully("Proveedor habilitado con exito");
    }

    //CONSULTAS
    //todos los proveedores
    public function getAllSuplliers(){
        $suppliers = SupplierModel::getAllSuppliers();
        echo $this->response->successfully("Todos los proveedores, $suppliers");
        die();
    }

    //proveedores activos
    public function getActiveSuppliers(){
        $suppliers = SupplierModel::getActiveSuppliers();
        echo $this->response->successfully("Lista de proveedores activos, $suppliers");
        die();
    }
    
    //proveedores dados de baja
    public function getDisabledSuppliers(){
        $suppliers = SupplierModel::getDisabledSuppliers();
        echo $this->response->successfully("Lista de proveedores dados de baja, $suppliers");
        die();
    }

    //buscar proveedor por rut
    public function getSupplierByRut($rut){
        $suppliers = SupplierModel::getSupplierByRut($rut);
        echo $this->response->successfully("Proveedor por numero de RUT, $suppliers");
        die();
    }

    //buscar proveedor por nombre
    public function getSupplierByName($company){
        $suppliers = SupplierModel::getSupplierByName($company);
        echo $this->response->successfully("Proveedor por nombre, $suppliers");
        die();
    }

    //UPDATE
    public function updateSupplier($supplierData){
        $this->jwt->verifyTokenAndGetIdUserFromRequest();
        $bodyIsValid = $this->validateBodyOfSupplier($supplierData);
        if(!$bodyIsValid){
            echo $this->response->error400("Error al enviar los datos");
            die();
        }
        $rut = $supplierData['rut'];
        $company = $supplierData['company'];
        $phone = $supplierData['phone'];
        $address = $supplierData['address'];

        //Validamos que el proveedor que se quiere modificar exista
        $supplierToUpdate = SupplierModel::getSupplierByRut($rut);
        if (!$supplierToUpdate){
            echo $this->response->error203("El proveedor que quiere actualizar no existe en el sistema");
            die();
        }

        //Actualizamos los datos
        $result = SupplierModel::updateSupplier($rut, $company, $address, $phone);
        if (!$result){
            echo $this->response->error500("No se pudo actualizar el proveedor");
            die();
        }
        echo $this->response->successfully("Proveedor actualizado!");
    }

    //Dar de baja un proveedor
    public function disableSupplier($supplierData){
        $this->jwt->verifyTokenAndGetIdUserFromRequest();
        $bodyIsValid = $this->validateBodyOfSupplier($supplierData);
        if (!$bodyIsValid){
            echo $this->response->error400("Error al enviar los datos");
            die();
        }
        $rut = $supplierData['rut'];
        $company = $supplierData['company'];

        //validamos que el proveedor exista y este activo
        $supplierToDisable = SupplierModel::getActiveSupplierByRut($rut);
        if (!$supplierToDisable){
            echo $this->response->error203("El proveedor que dar de baja no existe");
            die();
        }

        //Damos de baja el proveedor
        $result = SupplierModel::disableSupplier($rut);
        if (!$result){
            echo $this->response->error400("No se pudo dar de baja el proveedor");
            die();
        }
        echo $this->response->successfully("Proveedor dado de baja con exito");
    }
}

