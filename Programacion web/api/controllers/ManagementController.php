<?php

include_once('./helpers/Response.php');
include_once("./helpers/Token.php");
include_once("./models/ManagementModel.php");
include_once("./models/SaleModel.php");
include_once("./models/SupplyModel.php");
include_once("./models/ManagementModel.php");

class ManagementController {

    private $response;
    private $jwt;
    
    function __construct()
    {
        $this->response = new Response();
        $this->jwt = new Token();
    }

    //consultas
    public function getBalances(){
        $employeeRole = $this->jwt->verifyTokenAndValidateEmployeeUser();
        if(!$employeeRole){
            echo $this->response->error203("PERMISO DENEGADO");
            die();
        }
        if($employeeRole != 'JEFE'){
            http_response_code(401);
            echo $this->response->error401("Rol no valido para relizar esta accion");
            die();
        }
        $management = ManagementModel::getBalances(); 
        $balance = array();
        $totalSale = $management['totalSale'];
        $totalSupply = $management['totalSupply']*-1;
        $diference = ($totalSale + $totalSupply);
        array_push( $balance, array( "TotalSale" => $totalSale,"TotalSupply" => $totalSupply,"Diference" => $diference)); 
        echo $this->response->successfully("Balance de saldos:",$balance);
    }
    public function getBestClients($limit){
        $employeeRole = $this->jwt->verifyTokenAndValidateEmployeeUser();
        if(!$employeeRole){
            echo $this->response->error203("PERMISO DENEGADO");
            die();
        }
        if($employeeRole != 'JEFE'){
            http_response_code(401);
            echo $this->response->error401("Rol no valido para relizar esta accion");
            die();
        }
        if($limit<0){     
            echo $this->response->error203("El limite $limit no es correcto");
            die();

        }
        $management = ManagementModel::getBestClients($limit);
        $clients= array();
        foreach($management as $client){
            $isBusiness = $client["companyName"];
            if(!$isBusiness){
            array_push( $clients, array("idClient" => $client['idClient'],"clientInfo" => $client['clientInfo'],"mailClient" => $client['mailClient'],"spentMoney" => $client['spentMoney'],"totalSales" => $client['totalSales']));    
        }else{
            array_push( $clients, array("idClient" => $client['idClient'],"clientName" => $client['clientName'],"comapnyRut" => $client['companyRut'],"mailClient" => $client['mailClient'],"spentMoney" => $client['spentMoney'],"totalSales" => $client['totalSales']));    
            }
        }
        $response = array("clients"=>$clients);
        echo $this->response->successfully("Los mejores $limit clientes", $response);
    }
    public function getBestProducts($limit){
        $employeeRole = $this->jwt->verifyTokenAndValidateEmployeeUser();
        if(!$employeeRole){
            echo $this->response->error203("PERMISO DENEGADO");
            die();
        }
        if($employeeRole != 'JEFE'){
            http_response_code(401);
            echo $this->response->error401("Rol no valido para relizar esta accion");
            die();
        }
        if($limit<0){     
            echo $this->response->error203("El limite $limit no es correcto");
            die();

        }
        $management = ManagementModel::getBestProducts($limit);
        $products= array();
        foreach($management as $product){
            array_push( $products, array("totalSales" => $product['totalSales'],"totalRaised" => $product['totalRaised'],"barcode" => $product['barcode'],"product" => $product['product']));    
        }
        echo $this->response->successfully("Los $limit productos mas vendidos", $products);
    }

}