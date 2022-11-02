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

    private function validateBodyOfStatus($statusData){
        if( !isset($statusData['name']) ||  !isset($statusData['description']) ) return false;
        return $statusData;
    }
    private function validateDescripionOfUpdate($statusData){
        if(!isset($statusData['description']) ) return false;
        return $statusData;
    }
    public function saveStatus($statusData){
        /*
            En este metodo no precisamos el ID del usuario, lo unico que validamos es que tenga un token y sea valido. 
            Si no tiene token, no pasa de la funcion para abajo por que el metodo mismo le niega el acceso.
        */
        $this->jwt->verifyTokenAndGetIdUserFromRequest(); 
        $bodyIsValid = $this->validateBodyOfStatus($statusData);
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
    //consultas
    public function getAllStatus(){
        $state = StatusModel::getAllStatus();
        echo $this->response->successfully("Estados obtenidos:",$state);
    }

    public function getStatusByName($name){
        $state = StatusModel::getStatusByName($name);
        if(!$state){
            echo $this->response->error200("El estado con nombre $name no existe");
            die();
        }
        echo json_encode($state);  
    }
    public function getStatusById($idState){
        $state = StatusModel::getStatusById($idState);
        if(!$state){
            echo $this->response->error200("No existe estado para id ingresado");
            die();
        }
        echo json_encode($state);  
    }

    public function updateStatus($statusId, $statusData){

        $bodyIsValid = $this->validateDescripionOfUpdate($statusData);
        if(!$bodyIsValid) echo $this->response->error400();

        $descriptionStatus = $statusData['description'];

        $existStatus = StatusModel::getStatusById($statusId);
        if (!$existStatus){
            echo $this->response->error200('El id del estado no existe');
            die();
        }

        $result = StatusModel::updateDescriptionStatus($statusId, $descriptionStatus);
        if(!$result){
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Descripcion del estado actualizado con exito");
    }
}