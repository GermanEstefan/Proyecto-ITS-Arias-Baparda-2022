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
    public function getSaleId($idSale){
        $sale = SaleModel::getSaleById($idSale);
        if(!$sale){
            echo $this->response->error203("No se encuentra venta con id $idSale");
            die();
        }
        //Data en comun
        $id = $sale["id_sale"];
        $saleDate = $sale["saleDate"];
        $statusActual = $sale["statusActual"];
        //Datos de direccion
        $packOff = array();
        array_push( $packOff, array( "address" => $sale['address'],"delivery" => $sale['delivery']));

        $infoCustomer = array();
        $isBusiness = ["razonSocial"];
        if(!$isBusiness){
            array_push( $infoCustomer, array( "name" => $sale['name'],"lastname" => $sale['lastname']));    
        }
        array_push( $infoCustomer, array( "razonSocial" => $sale['razonSocial'],"rut" => $sale['rut'],"name" => $sale['name'],"lastname" => $sale['lastname']));
        
        $infoPayment = array();
        array_push( $infoPayment, array( "payment" => $sale['payment'],"total" => $sale['total']));
        
        $response = array("id" => $id,"saleDate" => $saleDate, "statusActual" => $statusActual, "packOff" => $packOff, "infoCustomer" => $infoCustomer, "infoPayment" => $infoPayment);
        echo $this->response->successfully("Venta encontrada:", $response);  
    }
    
    //ACTUALIZAR
   

    //ELIMINAR
}

?>