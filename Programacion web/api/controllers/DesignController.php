<?php

include_once('./helpers/Response.php');
include_once("./helpers/Token.php");
include_once("./models/DesignModel.php");
include_once("./models/ProductModel.php");

class DesignController {

    private $response;
    private $jwt;
    
    function __construct()
    {
        $this->response = new Response();
        $this->jwt = new Token();
    }

    private function validateBodyOfDesign($designData){
        if( !isset($designData['name'])
        ||  !isset($designData['description']))
        return false;
        //aca tenemos que validar mas cosas como que tenga un largo especifico (se pueden enviar nombre de ctegoria con valor " ")
        return $designData;
    }
    //ALTA
    public function saveDesign($designData){
        /*
            En este metodo no precisamos el ID del usuario, lo unico que validamos es que tenga un token y sea valido. 
            Si no tiene token, no pasa de la funcion para abajo por que el metodo mismo le niega el acceso.
        */
        $this->jwt->verifyTokenAndGetIdUserFromRequest(); 
        $bodyIsValid = $this->validateBodyOfDesign($designData);
        if(!$bodyIsValid){
             echo $this->response->error400('Error en los datos enviados');
        die();
        }
        
        $name = $designData['name'];
        $description = $designData['description'];

        $designExist = DesignModel::getDesignByName($name);
        if($designExist){
            echo $this->response->error203("el talle con el nombre $name ya existe");
            die();
        }
        $design = new DesignModel($name, $description);
        $result = $design->save();
        if(!$result){
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Diseño creado con exito");
    }
    //CONSULTAS
    public function getDesigns(){
        $designs = DesignModel::getAllDesigns();
        if(!$designs){
            echo $this->response->error203("No hay Diseños");
            die();
        }
        echo json_encode($designs);
    }   

    public function getDesignName($name){
        $design = DesignModel::getDesignByName($name);
        if(!$design){
            echo $this->response->error203("El diseño con el nombre $name no existe");
            die();
        }
        echo json_encode($design);  
    }
    public function getDesignId($idDesign){
        $design = DesignModel::getDesignById($idDesign);
        if(!$design){
            echo $this->response->error203("El diseño con id $idDesign no existe");
            die();
        }
        echo json_encode($design);  
    }
    //MODIFICACIONES
    public function updateDesign($idDesign,$designData){
        $this->jwt->verifyTokenAndGetIdUserFromRequest(); 
        $bodyIsValid = $this->validateBodyOfDesign($designData);
        if(!$bodyIsValid) {
        echo $this->response->error400('Error en los datos enviados');
        die();
        }

        $nameDesign = $designData['name'];
        $descriptionDesign = $designData['description'];
                
        $existDesign = DesignModel::getDesignById($idDesign);
        if (!$existDesign){
            echo $this->response->error203('El diseño indicado no es correcto');
            die();
        }
        $notChangeName = DesignModel::getDesignByName($nameDesign);
        if ($notChangeName){
            $result = DesignModel::updateDesignNotName($idDesign,$descriptionDesign);
            echo $this->response->successfully("Diseño actualizado con exito");
            die();
        }
        $result = DesignModel::updateDesign($idDesign,$nameDesign,$descriptionDesign);
        if(!$result){
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Diseño actualizado con exito");
    }
    //ELIMINAR
    public function deleteDesign($idDesign){
        $this->jwt->verifyTokenAndGetIdUserFromRequest();
        //Valido que el diseño exista
        $existDesign = DesignModel::getDesignById($idDesign);
        if (!$existDesign){
            echo $this->response->error203('Diseño indicado no es correcto');
            die();
        }
        //Valido que el diseño no se este usando por un producto
        $prodUsaDesign = ProductModel::getProductsByIdDesign($idDesign);
        if ($prodUsaDesign){
            echo $this->response->error203("Error El diseño $idDesign esta siendo usado en un producto");
            die();
        }
        $result = DesignModel::deleteDesign($idDesign);
        if(!$result){
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Diseño eliminado correctamente");
    }
}
?>