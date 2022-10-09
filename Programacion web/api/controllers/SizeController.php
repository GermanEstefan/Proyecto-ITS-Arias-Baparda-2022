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
    //CREAR
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
    //CONSULTAR
    public function getSizes(){
        $cateogrysToJson = json_encode(SizeModel::getAllSizes()); 
        echo $cateogrysToJson;
    }

    public function getSize($idSize){
        $size = SizeModel::getSizeById($idSize);
        if(!$size){
            echo $this->response->error200("El talle con el ID $idSize no existe");
            die();
        }
        echo $this->response->successfully("Talle obtenido con exito", $size);  
    }
    //EDITAR
    public function updateSize($idSize,$sizeData){
        $this->jwt->verifyTokenAndGetIdUserFromRequest();
        $bodyIsValid = $this->validateBodyOfSize($sizeData);
        if(!$bodyIsValid) echo $this->response->error400();

        $nameSize = $sizeData['name'];
        $descriptionSize = $sizeData['description'];

        $existSize = SizeModel::getSizeById($idSize);
        if (!$existSize){
            echo $this->response->error200('El id del talle enviado no existe');
            die();
        }

        $existName = SizeModel::getSizeByName($nameSize);
        if ($existName){
            echo $this->response->error200('El nombre del talle ya existe');
            die();
        }
        $result = SizeModel::updateSize($idSize, $nameSize, $descriptionSize);
        if(!$result){
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Talle actualizada con exito");
    }
    //BORRAR
    public function deleteSize($idSize){
        $this->jwt->verifyTokenAndGetIdUserFromRequest();
        $existSize = SizeModel::getSizeById($idSize);
        if (!$existSize){
            echo $this->response->error200('El id del talle enviado no existe');
            die();
        }

        $result = SizeModel::deleteSize($idSize);
        if(!$result){
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Talle eliminado exitosamente");
    }
}

?>