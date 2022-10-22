<?php

include_once('./helpers/Response.php');
include_once("./helpers/Token.php");
include_once("./models/SaleModel.php");
include_once("./models/DeliveryModel.php");
include_once("./models/UserModel.php");

class SaleController {

    private $response;
    private $jwt;
    
    function __construct()
    {
        $this->response = new Response();
        $this->jwt = new Token();
    }

    private function validateBodyOfSale($saleData){
        if( !isset($saleData['address']) 
        ||  !isset($saleData['client'])
        ||  !isset($saleData['delivery'])
        ||  !isset($saleData['payment'])
        ||  !isset($saleData['products']))
        return false;
        return $saleData;
    }
    //ALTA
    public function saveSale($saleData){
        $this->jwt->verifyTokenAndGetIdUserFromRequest(); 
        $bodyIsValid = $this->validateBodyOfSale($saleData);
        if(!$bodyIsValid){
             echo $this->response->error400('La informacion recibida es incorrecta');
            die();
        }

        $address = $saleData['address'];
        $client = $saleData['client'];
        $delivery = $saleData['delivery'];
        $payment = $saleData['payment'];
        $productsForSale = $saleData ['products'];
        $mailExist = UserModel::validEmailForSale($client);
        if (!$mailExist) {
            echo $this->response->error203("El mail no existe");
            die();
        }
        $analyzeMail = $mailExist["state"];
        if($analyzeMail == 0){
            echo $this->response->error203("El usuario se encuentra inactivo");
            die();
        }
        $identifyClient = SaleModel::getInfoCustomerByEmail($client);
        $idClient = ($identifyClient["id_user"]);
        
        $saleCreate = new SaleModel($address, $idClient, $delivery,$payment, $productsForSale);
        $result= $saleCreate->saveSale($productsForSale);
        if(!$result){
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Su pedido fue realizado con exito");
    }
    //CONSULTAS
    
    //ACTUALIZAR
   

    //ELIMINAR
}

?>