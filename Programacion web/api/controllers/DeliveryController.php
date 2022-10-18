<?php

include_once('./helpers/Response.php');
include_once("./helpers/Token.php");
include_once("./models/DeliveryModel.php");


class DeliveryController {

    private $response;
    private $jwt;
    
    function __construct()
    {
        $this->response = new Response();
        $this->jwt = new Token();
    }

    private function validateBodyOfDelivery($deliveryData){
        if( !isset($deliveryData['name']) 
        ||  !isset($deliveryData['description']))
        return false;
        //aca tenemos que validar mas cosas como que tenga un largo especifico (se pueden enviar nombre de ctegoria con valor " ")
        return $deliveryData;
    }
    //ALTA
    public function saveDelivery($deliveryData){
        /*
            En este metodo no precisamos el ID del usuario, lo unico que validamos es que tenga un token y sea valido. 
            Si no tiene token, no pasa de la funcion para abajo por que el metodo mismo le niega el acceso.
        */
        $this->jwt->verifyTokenAndGetIdUserFromRequest(); 
        $bodyIsValid = $this->validateBodyOfDelivery($deliveryData);
        if(!$bodyIsValid){
             echo $this->response->error400('Error en los datos enviados');
        die();
        }
        
        $name = $deliveryData['name'];
        $description = $deliveryData['description'];

        $deliveryExist = DeliveryModel::getDeliveryByName($name);
        if($deliveryExist){
            echo $this->response->error203("el horario con el nombre $name ya existe");
            die();
        }
        $delivery = new DeliveryModel($name, $description);
        $result = $delivery->save();
        if(!$result){
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Horario de entrega creado con exito");
    }
    //CONSULTAS
    public function getDeliverys(){
        $deliverys = DeliveryModel::getAllDeliverys();
        echo $this->response->successfully("Horarios de entrega Obtenidos:",$deliverys);
        die();
        }
    public function getDeliveryName($name){
        $delivery = DeliveryModel::getDeliveryByName($name);
        if(!$delivery){
            echo $this->response->error203("El horario con el nombre $name no existe");
            die();
        }
        echo $this->response->successfully("Horario de entrega encontrado:", $delivery);  
    }
    public function getDeliveryId($idDelivery){
        $delivery = DeliveryModel::getDeliveryById($idDelivery);
        if(!$delivery){
            echo $this->response->error203("El Horario de entrega con id $idDelivery no existe");
            die();
        }
        echo $this->response->successfully("Horario de entrega encontrado:", $delivery);  
    }
    //ACTUALIZAR
    public function updateDelivery($idDelivery,$deliveryData){
        $this->jwt->verifyTokenAndGetIdUserFromRequest(); 
        $bodyIsValid = $this->validateBodyOfDelivery($deliveryData);
        if(!$bodyIsValid) {
        echo $this->response->error400('Error en los datos enviados');
        die();
        }

        $nameDelivery = $deliveryData['name'];
        $descriptionDelivery = $deliveryData['description'];
                
        $existDelivery = DeliveryModel::getDeliveryById($idDelivery);
        if (!$existDelivery){
            echo $this->response->error203('El Horario de entrega indicado no es correcto');
            die();
        }

        $notChangeName = DeliveryModel::getDeliveryByName($nameDelivery);
        if ($notChangeName){
            $result = DeliveryModel::updateDeliveryNotName($idDelivery,$descriptionDelivery);
            echo $this->response->successfully("Horario de entrega actualizado con exito");
            die();
        }
        
        $result = DeliveryModel::updateDelivery($idDelivery,$nameDelivery,$descriptionDelivery);
        if(!$result){
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Horario de entrega actualizado con exito");
    }
    //ELIMINAR
    public function deleteDelivery($idDelivery){
        $this->jwt->verifyTokenAndGetIdUserFromRequest();
        //Valido que el talle exista
        $existDelivery = DeliveryModel::getDeliveryById($idDelivery);
        if (!$existDelivery){
            echo $this->response->error203('Horario indicado no es correcto');
            die();
        }
        
        $result = DeliveryModel::deleteDelivery($idDelivery);
        if(!$result){
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Horario de entrega eliminado correctamente");
    }
}

?>