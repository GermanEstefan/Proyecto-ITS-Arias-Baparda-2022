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

    private function validateBodyOfstatus($statusData){
        if( !isset($statusData['name']) 
        ||  !isset($statusData['description']))
        return false;
        //aca tenemos que validar mas cosas como que tenga un largo especifico (se pueden enviar nombre de ctegoria con valor " ")
        return $statusData;
    }
    //ALTA
    public function savestatus($statusData){
        /*
            En este metodo no precisamos el ID del usuario, lo unico que validamos es que tenga un token y sea valido. 
            Si no tiene token, no pasa de la funcion para abajo por que el metodo mismo le niega el acceso.
        */
        $this->jwt->verifyTokenAndGetIdUserFromRequest(); 
        $bodyIsValid = $this->validateBodyOfstatus($statusData);
        if(!$bodyIsValid){
             echo $this->response->error400('Error en los datos enviados');
        die();
        }
        
        $name = $statusData['name'];
        $description = $statusData['description'];
        //Valido que no exista el estado x el nombre (unique)
        $statusExist = statusModel::getStatusByName($name);
        if($statusExist){
            echo $this->response->error203("el estado con el nombre $name ya existe");
            die();
        }
        $status = new statusModel($name, $description);
        $result = $status->save();
        if(!$result){
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Estado creado con exito");
    }
    //CONSULTAS
    public function getAllStatus(){
        $statuss = statusModel::getAllStatus();
        echo $this->response->successfully("Estados Obtenidos:",$statuss);
        die();
        }
    public function getstatusName($name){
        $status = statusModel::getStatusByName($name);
        if(!$status){
            echo $this->response->error203("El estado con el nombre $name no existe");
            die();
        }
        echo $this->response->successfully("Estado encontrado:", $status);  
    }
    public function getStatusId($idstatus){
        $status = statusModel::getStatusById($idstatus);
        if(!$status){
            echo $this->response->error203("El estado con id $idstatus no existe");
            die();
        }
        echo $this->response->successfully ("Estado encontrado:", $status);  
    }
    //ACTUALIZAR
    public function updateStatus($idstatus,$statusData){
        $this->jwt->verifyTokenAndGetIdUserFromRequest(); 
        $bodyIsValid = $this->validateBodyOfstatus($statusData);
        if(!$bodyIsValid) {
        echo $this->response->error400('Error en los datos enviados');
        die();
        }

        $namestatus = $statusData['name'];
        $descriptionstatus = $statusData['description'];
                
        $existstatus = statusModel::getStatusById($idstatus);
        if (!$existstatus){
            echo $this->response->error203('El estado indicado no es correcto');
            die();
        }

        $notChangeName = statusModel::getStatusByName($namestatus);
        if ($notChangeName){
            $result = statusModel::updateStatusNotName($idstatus,$descriptionstatus);
            echo $this->response->successfully("Estado actualizado con exito");
            die();
        }
        
        $result = statusModel::updateStatus($idstatus,$namestatus,$descriptionstatus);
        if(!$result){
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Estado actualizado con exito");
    }
}

?>