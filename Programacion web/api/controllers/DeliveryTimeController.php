<?php

include_once('./database/Connection.php');
include_once('./helpers/Response.php');
include_once("./helpers/Token.php");
include_once("./models/DeliveryTimeModel.php");


class DeliveryTimeController {

    private $response;
    private $jwt;
    
    function __construct()
    {
        $this->response = new Response();
        $this->jwt = new Token();
    }

    private function validateBodyOfDeliveryTime($deliveryData){
        if( !isset($deliveryData['name']) 
        ||  !isset($deliveryData['description'])) 
        return false;
        //aca tenemos que validar mas cosas como que tenga un largo especifico (se pueden enviar nombre de ctegoria con valor " ")
        return $deliveryData;
    }
    //ALTA
    public function saveDeliveryTime($deliveryData){
        /*
            En este metodo no precisamos el ID del usuario, lo unico que validamos es que tenga un token y sea valido. 
            Si no tiene token, no pasa de la funcion para abajo por que el metodo mismo le niega el acceso.
        */
        $this->jwt->verifyTokenAndGetIdUserFromRequest(); 
        $bodyIsValid = $this->validateBodyOfDeliveryTime($deliveryData);
        if(!$bodyIsValid){
             echo $this->response->error400('Error en los datos enviados');
        die();
        }
        
        $name = $deliveryData['name'];
        $description = $deliveryData['description'];

        $delivery = new DeliveryTimeModel($name, $description);
        $result = $delivery->save();
        if(!$result){
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Rango horario creado con exito");
    }
    //CONSULTAS
    public function getDeliveryTimes(){
        $delivery = DeliveryTimeModel::getAllDeliveryTime();
        echo $this->response->successfully('Rangos horarios obtenidas:',$delivery);
        die();
    }    

    public function getDeliveryTimeId($idDeliveryTime){
        $delivery = DeliveryTimeModel::getDeliveryTimeById($idDeliveryTime);
        if(!$delivery){
            echo $this->response->error203("No existe rango horario para la id $idDeliveryTime ");
            die();
        }
        echo $this->response->successfully("Rango horario obtenido:", $delivery); 
    }
    //MODIFICACIONES
    public function updateDeliveryTime($idDeliveryTime,$deliveryData){
        $this->jwt->verifyTokenAndGetIdUserFromRequest(); 
        $bodyIsValid = $this->validateBodyOfDeliveryTime($deliveryData);
        if(!$bodyIsValid) {
        echo $this->response->error400('Error en los datos enviados');
        die();
        }

        $nameDeliveryTime = $deliveryData['name'];
        $descriptionDeliveryTime = $deliveryData['description'];
        //Validamos que exista la categoria
        $existDeliveryTime = DeliveryTimeModel::getDeliveryTimeById($idDeliveryTime);
        if (!$existDeliveryTime){
            echo $this->response->error203('Id indicado no es correcto');
            die();
        }

        $result = DeliveryTimeModel::updateDeliveryTime($idDeliveryTime,$nameDeliveryTime,$descriptionDeliveryTime);
        if(!$result){
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Rango horario actualizado con exito");
    }
    //ELIMINAR
    public function deleteDeliveryTime($idDeliveryTime){
        $this->jwt->verifyTokenAndGetIdUserFromRequest();
        
        //VALIDAR QUE NO ESTE ASIGNADO A NINGUNA VENTA
        /*$existDeliveryTime = DeliveryTimeModel::getDeliveryTimeById($idDeliveryTime);
        if (!$existDeliveryTime){
            echo $this->response->error203('La categoria indicada no es correcta');
            die();
        }
        */
        $result = DeliveryTimeModel::deleteDeliveryTime($idDeliveryTime);
        if(!$result){
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Rango horario eliminado con exito exitosamente");
    }
}

?>