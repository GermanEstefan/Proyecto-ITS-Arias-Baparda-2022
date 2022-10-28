<?php

include_once('./helpers/Response.php');
include_once("./helpers/Token.php");
include_once("./models/SupplyModel.php");
include_once("./models/SupplierModel.php");
include_once("./models/DeliveryModel.php");
include_once("./models/UserModel.php");
include_once("./models/EmployeeModel.php");
include_once("./models/StatusModel.php");
class SupplyController {

    private $response;
    private $jwt;
    
    function __construct()
    {
        $this->response = new Response();
        $this->jwt = new Token();
    }

    
    private function validateBodyOfSupply($supplyData){
        if(!isset($supplyData['idSupplier'])
        ||  !isset($supplyData['employee_ci'])
        ||  !isset($supplyData['comment'])
        ||  !isset($supplyData['products']))
        return false;
        return $supplyData;
    }
    //ALTA
    public function saveSupply($supplyData){
        $this->jwt->verifyTokenAndGetIdUserFromRequest(); 
        $bodyIsValid = $this->validateBodyOfSupply($supplyData);
        if(!$bodyIsValid){
            echo $this->response->error400('La informacion recibida es incorrecta');
            die();
        }

        $idSupplier = $supplyData['idSupplier'];
        $employee_ci = $supplyData['employee_ci'];
        $comment = $supplyData['comment'];
        $productsForSupply = $supplyData ['products'];
        
        $supplierExist = SupplierModel::getSupplierById($idSupplier);
        if (!$supplierExist) {
            echo $this->response->error203("El Proveedor indicado no es correcto");
            die();
        }
        $supplierIsActive = SupplierModel::checkStatusSupplier($idSupplier);
        if (!$supplierIsActive) {
            echo $this->response->error203("El proveedor se encuentra INACTIVO");
            die();
        }

        $supplyCreate = new SupplyModel($idSupplier,$employee_ci,$comment);
        $result= $supplyCreate->saveSupply($productsForSupply);
        if(!$result){
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Su aprovisionamiento fue realizado con exito");
    }

    //CONSULTAS
    public function getSupplyId($idSupply){
        $supply = SupplyModel::getSupplyById($idSupply);
        if(!$supply){
            echo $this->response->error203("No se encuentra compra para el id $idSupply");
            die();
        }
        //Data en comun
        $idSupply = $supply["idSupply"];
        $date = $supply["date"];
        $total = $supply["total"];
        //Datos del proveedor
        $infoSupplier = array();
        array_push( $infoSupplier, array( "idSupplier" => $supply["idSupplier"],"name" => $supply['name'],"rut" => $supply['rut']));
        
        $infoResponsible = array();
        array_push( $infoResponsible, array( "employeeDoc" => $supply['employeeDoc'],"employeeName" => $supply['employeeName'],"comment" => $supply['comment']));
        
        $response = array("idSupply" => $idSupply,"date" => $date, "total" => $total, "infoSupplier" => $infoSupplier, "infoResponsible" => $infoResponsible);
        echo $this->response->successfully("Compra encontrada:", $response);  
    }
    public function getDetailForSupply($idSupply){
        $supply = SupplyModel::getSupplyDetailById($idSupply);
        if(!$supply){
            echo $this->response->error203("No se encuentra compra con id $idSupply");
            die();
        }
        //Data en comun
        $idSupply = $supply[0]['idSupply'];
        $totalSupply = $supply[0]['totalSupply'];
        $details = array();
        foreach($supply as $detail){
        array_push( $details, array( "barcode" => $detail['barcode'],"nameProduct" => $detail['nameProduct'],"quantity" => $detail['quantity'],"costUnit" => $detail['costUnit'],"costTotal" => $detail['costTotal']));
        }
        $response = array("idSupply" => $idSupply,"totalSupply" =>$totalSupply, "details" => $details);
        echo $this->response->successfully("Detalle de compras para :$idSupply", $response);  
    }
    public function getAllSupplysForDay($day){
        $supply = SupplyModel::getAllSupplysByDay($day);
        if(!$supply){
            echo $this->response->error203("No se encuentran compras para la fecha $day");
            die();
        }
        $totalSpent = 0;
        $supplys = array();
        foreach($supply as $supplysInDay){
            $negativeBalance = ($supplysInDay["totalSupply"])*-1; 
            $totalSpent += $negativeBalance;           
            array_push( $supplys, array( "idSupply" => $supplysInDay['idSupply'],"date" => $supplysInDay['date'],"idEmployee" => $supplysInDay['idEmployee'],"ciEmployee" => $supplysInDay['ciEmployee'],"employeeName" => $supplysInDay['employeeName'],"totalSupply" => $supplysInDay['totalSupply']));

        }
        $totalSupplys = (count($supplys));    
        $response = array("TotalSupply" =>$totalSupplys,"totalSpent"=>$totalSpent, "supplys" => $supplys);
        echo $this->response->successfully("$totalSupplys Compras obtenidas para la fecha:$day", $response);  
     
    }
    public function getAllSupplys(){
        $supply = SupplyModel::getAllSupplys();
        
        $totalSpent = 0;
        $supplys = array();
        foreach($supply as $supplysInDay){
            $negativeBalance = ($supplysInDay["totalSupply"])*-1; 
            $totalSpent += $negativeBalance;           
            array_push( $supplys, array( "idSupply" => $supplysInDay['idSupply'],"date" => $supplysInDay['date'],"idEmployee" => $supplysInDay['idEmployee'],"ciEmployee" => $supplysInDay['ciEmployee'],"employeeName" => $supplysInDay['employeeName'],"totalSupply" => $supplysInDay['totalSupply']));

        }
        $totalSupplys = (count($supplys));    
        $response = array("TotalSupply" =>$totalSupplys,"totalSpent"=>$totalSpent, "supplys" => $supplys);
        echo $this->response->successfully("$totalSupplys Compras obtenidas para la fecha:", $response);  
     
    }

}

?>
