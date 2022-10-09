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
        if( !isset($sizeData['name']) 
        ||  !isset($sizeData['description']))
        return false;
        //aca tenemos que validar mas cosas como que tenga un largo especifico (se pueden enviar nombre de ctegoria con valor " ")
        return $sizeData;
    }
    
    public function saveSize($sizeData){
        /*
            En este metodo no precisamos el ID del usuario, lo unico que validamos es que tenga un token y sea valido. 
            Si no tiene token, no pasa de la funcion para abajo por que el metodo mismo le niega el acceso.
        */
        $this->jwt->verifyTokenAndGetIdUserFromRequest(); 
        $bodyIsValid = $this->validateBodyOfSize($sizeData);
        if(!$bodyIsValid){
             echo $this->response->error400('Error en los datos enviados');
        die();
        }
        
        $name = $sizeData['name'];
        $description = $sizeData['description'];

        $sizeExist = SizeModel::getSizeByName($name);
        if($sizeExist){
            echo $this->response->error203("el talle con el nombre $name ya existe");
            die();
        }
        $size = new SizeModel($name, $description);
        $result = $size->save();
        if(!$result){
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Talle creado con exito");
    }

    public function getSizes(){
        $sizesToJson = json_encode(SizeModel::getAllSizes()); 
        echo $sizesToJson;
    }

    public function getSizeName($name){
        $size = SizeModel::getSizeByName($name);
        if(!$size){
            echo $this->response->error203("El talle con el nombre $name no existe");
            die();
        }
        echo json_encode($size);  
    }
    public function getSizeId($idSize){
        $size = SizeModel::getSizeById($idSize);
        if(!$size){
            echo $this->response->error203("El talle con id $idSize no existe");
            die();
        }
        echo json_encode($size);  
    }
    public function updateSize($idSize,$sizeData){
        $this->jwt->verifyTokenAndGetIdUserFromRequest(); 
        $bodyIsValid = $this->validateBodyOfSize($sizeData);
        if(!$bodyIsValid) {
        echo $this->response->error400('Error en los datos enviados');
        die();
        }

        $nameSize = $sizeData['name'];
        $descriptionSize = $sizeData['description'];
                
        $existSize = SizeModel::getSizeById($idSize);
        if (!$existSize){
            echo $this->response->error203('El talle indicado no es correcto');
            die();
        }

        $notChangeName = SizeModel::getSizeByName($nameSize);
        if ($notChangeName){
            $result = SizeModel::updateSizeNotName($idSize,$descriptionSize);
            echo $this->response->successfully("Talle actualizado con exito");
            die();
        }
        
        $result = SizeModel::updateSize($idSize,$nameSize,$descriptionSize);
        if(!$result){
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Talle actualizado con exito");
    }

    public function deleteSize($idSize){
        $this->jwt->verifyTokenAndGetIdUserFromRequest();
        $existSize = SizeModel::getSizeById($idSize);
        if (!$existSize){
            echo $this->response->error203('Talle indicado no es correcto');
            die();
        }

        $result = SizeModel::deleteSize($idSize);
        if(!$result){
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Talle eliminado correctamente");
    }
}

?>