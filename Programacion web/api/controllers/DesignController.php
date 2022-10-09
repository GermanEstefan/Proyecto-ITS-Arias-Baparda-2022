<?php

include_once('./helpers/Response.php');
include_once("./helpers/Token.php");
include_once("./models/DesignModel.php");

class DesignController {

    private $response;
    private $jwt;
    
    function __construct()
    {
        $this->response = new Response();
        $this->jwt = new Token();
    }

    private function validateBodyOfDesign($designData){
        if( !isset($designData['name']) ||  !isset($designData['description']) ) return false;
        return $designData;
    }
    //CREAR
    public function saveDesign($designData){
        /*
            En este metodo no precisamos el ID del usuario, lo unico que validamos es que tenga un token y sea valido. 
            Si no tiene token, no pasa de la funcion para abajo por que el metodo mismo le niega el acceso.
        */
        $this->jwt->verifyTokenAndGetIdUserFromRequest(); 
        $bodyIsValid = $this->validateBodyOfDesign($designData);
        if(!$bodyIsValid) echo $this->response->error400();

        $name = $designData['name'];
        $description = $designData['description'];

        $designExist = DesignModel::getDesignByName($name);
        if($designExist){
            echo $this->response->error200("El diseño con nombre $name ya existe");
            die();
        }
        $desing = new DesignModel($name, $description);
        $result = $desing->save();
        if(!$result){
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Nuevo diseño dado de alta con exito");
    }
    //CONSULTAR
    public function getDesigns(){
        $designToJson = json_encode(DesignModel::getAllDesign()); 
        echo $designToJson;
    }

    public function getDesign($name){
        $design = DesignModel::getDesignByName($name);
        if(!$design){
            echo $this->response->error200("El diseño con nombre $name no existe");
            die();
        }
        echo json_encode($design);  
    }
    //EDITAR
    public function updateDesign($idDesign,$designData){

        $bodyIsValid = $this->validateBodyOfDesign($designData);
        if(!$bodyIsValid) echo $this->response->error400();

        $nameDesign = $designData['name'];
        $descriptionDesign = $designData['description'];

        $existDesign = DesignModel::getDesignById($idDesign);
        if (!$existDesign){
            echo $this->response->error200('El id del diseño enviado no existe');
            die();
        }

        $existName = DesignModel::getDesignByName($nameDesign);
        if ($existName){
            echo $this->response->error200('El nombre del diseño ya existe');
            die();
        }
        $result = DesignModel::updateDesign($idDesign, $nameDesign, $descriptionDesign);
        if(!$result){
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Diseño actualizado con exito");
    }
    //BORRAR
    
}

