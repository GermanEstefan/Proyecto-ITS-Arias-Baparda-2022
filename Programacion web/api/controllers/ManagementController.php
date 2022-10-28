<?php

include_once('./helpers/Response.php');
include_once("./helpers/Token.php");
include_once("./models/ManagementModel.php");
include_once("./models/SaleModel.php");
include_once("./models/SupplyModel.php");

class ManagementController {

    private $response;
    private $jwt;
    
    function __construct()
    {
        $this->response = new Response();
        $this->jwt = new Token();
    }

    private function validateBodyOfManagement($managementData){
        if( !isset($managementData['rut']) 
        ||  !isset($managementData['companyName']) 
        ||  !isset($managementData['address']) 
        ||  !isset($managementData['phone'])
        ) return false;

        return $managementData;
    }
    //consultas
    public function getBalances(){
        $management = ManagementModel::getBalances(); 
        echo $this->response->successfully("Balance de saldos:", $management);
    }

    
    
    //ACTUALIZAR
    public function updateManagement($managementId, $managementData){

        $this->jwt->verifyTokenAndGetIdUserFromRequest(); 
        $bodyIsValid = $this->validateBodyOfManagement($managementData);
        if(!$bodyIsValid) {
        echo $this->response->error400('Error en los datos enviados');
        die();
        }

        $rut = $managementData['rut'];
        $companyName = $managementData['companyName'];
        $address = $managementData['address'];
        $phone = $managementData['phone'];

        $existManagement = ManagementModel::getManagementById($managementId);
        if (!$existManagement){
            echo $this->response->error203('El id del proveedor no existe');
            die();
        }
        $notChangeRut = ManagementModel::getManagementByRut($rut);
        if ($notChangeRut){
            $result = ManagementModel::updateManagementNotRut($managementId,$companyName,$address,$phone);
            echo $this->response->successfully("Informacion del proveedor actualizada con exito");
            die();
        }

        $result = ManagementModel::updateManagement($managementId,$rut,$companyName,$address,$phone);
        if(!$result){
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Proveedor actualizado con exito");
    }
    public function disableManagement($managementId)
    {
        $this->jwt->verifyTokenAndGetIdUserFromRequest();
        //Valido que exista el proveedor
        $managementExist = ManagementModel::getManagementById($managementId);
        if (!$managementExist) {
            echo $this->response->error203("Esta intentando deshabilitar un proveedor que no existe");
            die();
        }
        $result = ManagementModel::disableManagement($managementId);
        if (!$result) {
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Proveedor deshabilitado exitosamente");
    }
    public function activeManagement($managementId)
    {
        $this->jwt->verifyTokenAndGetIdUserFromRequest();
        //Valido que exista el proveedor
        $managementExist = ManagementModel::getManagementById($managementId);
        if (!$managementExist) {
            echo $this->response->error203("Esta intentando activar un proveedor que no existe");
            die();
        }
        $result = ManagementModel::activeManagement($managementId);
        if (!$result) {
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Proveedor activado exitosamente");
    }
}