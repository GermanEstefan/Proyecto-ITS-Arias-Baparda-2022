<?php

include_once('./helpers/Response.php');
include_once("./helpers/Token.php");
include_once("./models/SupplierModel.php");

class SupplierController {

    private $response;
    private $jwt;
    
    function __construct()
    {
        $this->response = new Response();
        $this->jwt = new Token();
    }

    private function validateBodyOfSupplier($supplierData){
        if( !isset($supplierData['rut']) 
        ||  !isset($supplierData['companyName']) 
        ||  !isset($supplierData['address']) 
        ||  !isset($supplierData['phone'])
        ) return false;

        return $supplierData;
    }
    public function saveSupplier($supplierData)
    {
        /*
            En este metodo no precisamos el ID del usuario, lo unico que validamos es que tenga un token y sea valido. 
            Si no tiene token, no pasa de la funcion para abajo por que el metodo mismo le niega el acceso.
        */
        $this->jwt->verifyTokenAndGetIdUserFromRequest(); 
        $bodyIsValid = $this->validateBodyOfSupplier($supplierData);
        if(!$bodyIsValid) {
            echo $this->response->error400('Error en los datos enviados');
        die();
        }

        $rut = $supplierData['rut'];
        $companyName = $supplierData['companyName'];
        $address = $supplierData['address'];
        $phone = $supplierData['phone'];

        $supplierExist = SupplierModel::getSupplierByRut($rut);
        if($supplierExist){
            echo $this->response->error203("El $rut ya existe");
            die();
        }
        $supplier = new SupplierModel($rut, $companyName, $address, $phone);
        $result = $supplier->save();
        if(!$result){
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Nuevo proveedor dado de alta con exito");
    }
    //consultas
    public function getAllSuppliers(){
        $supplier = SupplierModel::getAllSuppliers(); 
        echo $this->response->successfully("Proveedores del sistema:", $supplier);
    }
    public function getAllSuppliersActive(){
        $supplier = SupplierModel::getAllSuppliersActive(); 
        echo $this->response->successfully("Proveedores Activos:", $supplier);
    }
    public function getAllSuppliersDisable(){
        $supplier = SupplierModel::getAllSuppliersDisable(); 
        echo $this->response->successfully("Proveedores Inactivos:", $supplier);
    }
    public function getSupplierByRut($rut){
        $supplier = SupplierModel::getSupplierByRut($rut);
        if(!$supplier){
            echo $this->response->error203("El proveedor $rut no existe");
            die();
        }
        echo $this->response->successfully("Proveedor con rutN°:$rut", $supplier);  
    }

    public function getSupplierByName($companyName){
        $supplier = SupplierModel::getSupplierByName($companyName);
        if(!$supplier){
            echo $this->response->error203("El proveedor con nombre $companyName no existe");
            die();
        }
        echo $this->response->successfully("Proveedor con nombre:$companyName", $supplier);
    }
    public function getSupplierById($idSupplier){
        $supplier = SupplierModel::getSupplierById($idSupplier);
        if(!$supplier){
            echo $this->response->error203("No existe proveedor con id $idSupplier");
            die();
        }
        echo $this->response->successfully("Proveedor con idN°:$idSupplier", $supplier);
    }
    //ACTUALIZAR
    public function updateSupplier($supplierId, $supplierData){

        $this->jwt->verifyTokenAndGetIdUserFromRequest(); 
        $bodyIsValid = $this->validateBodyOfSupplier($supplierData);
        if(!$bodyIsValid) {
        echo $this->response->error400('Error en los datos enviados');
        die();
        }

        $rut = $supplierData['rut'];
        $companyName = $supplierData['companyName'];
        $address = $supplierData['address'];
        $phone = $supplierData['phone'];

        $existSupplier = SupplierModel::getSupplierById($supplierId);
        if (!$existSupplier){
            echo $this->response->error203('El id del proveedor no existe');
            die();
        }
        $notChangeRut = SupplierModel::getSupplierByRut($rut);
        if ($notChangeRut){
            $result = SupplierModel::updateSupplierNotRut($supplierId,$companyName,$address,$phone);
            echo $this->response->successfully("Informacion del proveedor actualizada con exito");
            die();
        }

        $result = SupplierModel::updateSupplier($supplierId,$rut,$companyName,$address,$phone);
        if(!$result){
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Proveedor actualizado con exito");
    }
    public function disableSupplier($supplierId)
    {
        $this->jwt->verifyTokenAndGetIdUserFromRequest();
        //Valido que exista el proveedor
        $supplierExist = SupplierModel::getSupplierById($supplierId);
        if (!$supplierExist) {
            echo $this->response->error203("Esta intentando deshabilitar un proveedor que no existe");
            die();
        }
        $result = SupplierModel::disableSupplier($supplierId);
        if (!$result) {
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Proveedor deshabilitado exitosamente");
    }
    public function activeSupplier($supplierId)
    {
        $this->jwt->verifyTokenAndGetIdUserFromRequest();
        //Valido que exista el proveedor
        $supplierExist = SupplierModel::getSupplierById($supplierId);
        if (!$supplierExist) {
            echo $this->response->error203("Esta intentando activar un proveedor que no existe");
            die();
        }
        $result = SupplierModel::activeSupplier($supplierId);
        if (!$result) {
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Proveedor activado exitosamente");
    }
}