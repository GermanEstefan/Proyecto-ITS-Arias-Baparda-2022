<?php

include_once('./helpers/Response.php');
include_once("./helpers/Token.php");
include_once("./models/SizeModel.php");
include_once("./models/ProductModel.php");

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
    //ALTA
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
        //Valido que no exista el talle x el nombre (unique)
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
    //CONSULTAS
    public function getSizes(){
        $sizes = SizeModel::getAllSizes();
        if(!$sizes){
            echo $this->response->error203("No hay Talles");
            die();
        }
        echo json_encode($sizes);
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
    //ACTUALIZAR
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
    //ELIMINAR
    public function deleteSize($idSize){
        $this->jwt->verifyTokenAndGetIdUserFromRequest();
        //Valido que el talle exista
        $existSize = SizeModel::getSizeById($idSize);
        if (!$existSize){
            echo $this->response->error203('Talle indicado no es correcto');
            die();
        }
        //Valido que el talle no se este usando por un producto
        $prodUsaSize = ProductModel::getProductsByIdSize($idSize);
        if ($prodUsaSize){
            echo $this->response->error203("Error El talle $idSize esta siendo usado en un producto");
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