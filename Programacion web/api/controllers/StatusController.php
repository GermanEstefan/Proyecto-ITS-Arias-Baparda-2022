<?php

include_once('./helpers/Response.php');
include_once("./helpers/Token.php");
include_once("./models/StatusModel.php");

class StatusController {

    private $response;
    private $jwt;
    
    function __construct()
    {
        $this->response = new Response();
        $this->jwt = new Token();
    }

    private function validateBodyOfDesign($statusData){
        if( !isset($statusData['name']) ||  !isset($statusData['description']) ) return false;
        return $statusData;
    }
    
    public function saveDesign($statusData){
        /*
            En este metodo no precisamos el ID del usuario, lo unico que validamos es que tenga un token y sea valido. 
            Si no tiene token, no pasa de la funcion para abajo por que el metodo mismo le niega el acceso.
        */
        $this->jwt->verifyTokenAndGetIdUserFromRequest(); 
        $bodyIsValid = $this->validateBodyOfDesign($statusData);
        if(!$bodyIsValid) echo $this->response->error400();

        $name = $statusData['name'];
        $description = $statusData['description'];

        $statusExist = StatusModel::getStatusByName($name);
        if($statusExist){
            echo $this->response->error200("El estado con nombre $name ya existe");
            die();
        }
        $status = new StatusModel($name, $description);
        $result = $status->save();
        if(!$result){
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Nuevo estado dado de alta con exito");
    }

    public function getStatus(){
        $designToJson = json_encode(StatusModel::getAllStatus()); 
        echo $designToJson;
    }

    public function getState($name){
        $state = StatusModel::getStatusByName($name);
        if(!$state){
            echo $this->response->error200("El estado con nombre $name no existe");
            die();
        }
        echo json_encode($state);  
    }

    public function updateState($statusId, $statusData){

        $bodyIsValid = $this->validateBodyOfDesign($statusData);
        if(!$bodyIsValid) echo $this->response->error400();

        $nameStatus = $statusData['name'];
        $descriptionStatus = $statusData['description'];

        $existStatus = StatusModel::getStatusById($statusId);
        if (!$existStatus){
            echo $this->response->error200('El id del diseño enviado no existe');
            die();
        }

        $existName = StatusModel::getStatusByName($nameStatus);
        if ($existName){
            echo $this->response->error200('El nombre del estado ya existe');
            die();
        }
        $result = StatusModel::updateStatus($statusId, $nameStatus, $descriptionStatus);
        if(!$result){
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Diseño actualizado con exito");
    }
}