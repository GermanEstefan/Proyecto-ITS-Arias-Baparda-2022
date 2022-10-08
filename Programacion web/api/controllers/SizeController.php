<?php

include_once('./helpers/Response.php');
include_once("./helpers/Token.php");
include_once("./models/SizeModel.php");

class SizeController {

    private $response;
    private $jwt;
    
    function __construct()
    {
        $this->response = new Response();
        $this->jwt = new Token();
    }

    private function validateBodyOfSize($sizeData){
        if( !isset($sizeData['name']) ||  !isset($sizeData['description']) ) return false;
        return $sizeData;
    }
    
    public function saveSize($sizeData){
        /*
            En este metodo no precisamos el ID del usuario, lo unico que validamos es que tenga un token y sea valido. 
            Si no tiene token, no pasa de la funcion para abajo por que el metodo mismo le niega el acceso.
        */
        $this->jwt->verifyTokenAndGetIdUserFromRequest(); 
        $bodyIsValid = $this->validateBodyOfSize($sizeData);
        if(!$bodyIsValid) echo $this->response->error400();

        $name = $sizeData['name'];
        $description = $sizeData['description'];

        $sizeExist = SizeModel::getSizeByName($name);
        if($sizeExist){
            echo $this->response->error200("El talle con nombre $name ya existe");
            die();
        }
        $size = new SizeModel($name, $description);
        $result = $size->save();
        if(!$result){
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Talle dado de alta con exito");
    }

    public function getSizes(){
        $cateogrysToJson = json_encode(SizeModel::getAllSizes()); 
        echo $cateogrysToJson;
    }

    public function getSize($name){
        $size = SizeModel::getSizeByName($name);
        if(!$size){
            echo $this->response->error200("El talle con nombre $name no existe");
            die();
        }
        echo json_encode($size);  
    }

    public function updateSize($sizeData){

        $bodyIsValid = $this->validateBodyOfSize($sizeData);
        if(!$bodyIsValid) echo $this->response->error400();

        $nameSize = $sizeData['name'];
        $descriptionSize = $sizeData['description'];

        $nameExist = SizeModel::getSizeByName($nameSize);
        if($nameExist){
            echo $this->response->error200("El nombre de talle $nameSize ya existe");
            die();
        }

        $size = new SizeModel($nameSize, $descriptionSize);
        $result = $size->updateSize($nameSize, $descriptionSize);
        if(!$result){
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Talle actualizada con exito");
    }
}

?>