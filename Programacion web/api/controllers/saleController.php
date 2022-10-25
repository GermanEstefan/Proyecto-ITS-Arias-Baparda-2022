<?php

include_once('./helpers/Response.php');
include_once("./helpers/Token.php");
include_once("./models/SaleModel.php");
include_once("./models/DeliveryModel.php");
include_once("./models/UserModel.php");
include_once("./models/EmployeeModel.php");
include_once("./models/StatusModel.php");
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
    private function validateBodyOfReport($saleData){
        if(!isset($saleData['employeeDoc'])
        ||  !isset($saleData['status'])
        ||  !isset($saleData['comment']))
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
        $isBusiness = $sale["razonSocial"];
        if(!$isBusiness){
            //no es empresa, mando datos de consumidor final
            array_push( $infoCustomer, array("idUser" => $sale['idUser'], "name" => $sale['name'],"lastname" => $sale['lastname']));    
        }else{
        //Es empresa mando data de representante legal
        array_push( $infoCustomer, array( "idUser" => $sale['idUser'],"razonSocial" => $sale['razonSocial'],"rut" => $sale['rut'],"name" => $sale['name'],"lastname" => $sale['lastname']));
        }
        $infoPayment = array();
        array_push( $infoPayment, array( "payment" => $sale['payment'],"total" => $sale['total']));
        
        $response = array("id" => $id,"saleDate" => $saleDate, "statusActual" => $statusActual, "packOff" => $packOff, "infoCustomer" => $infoCustomer, "infoPayment" => $infoPayment);
        echo $this->response->successfully("Venta encontrada:", $response);  
    }
    public function getDetailForSale($idSale){
        $sale = SaleModel::getDetailSaleById($idSale);
        if(!$sale){
            echo $this->response->error203("No se encuentra venta con id $idSale");
            die();
        }
        //Data en comun
        $saleID = $sale[0]['sale_id'];
        $totalSale = $sale[0]['totalSale'];
        $details = array();
        foreach($sale as $detail){
        array_push( $details, array( "barcode" => $detail['barcode'],"product" => $detail['product'],"quantity" => $detail['quantity'],"total" => $detail['total']));
        }
        $response = array("saleID" => $saleID,"totalSale" =>$totalSale, "details" => $details);
        echo $this->response->successfully("Detalle de ventas para ID:$idSale", $response);  
    }
    public function getReportHistory($idSale){
        $sale = SaleModel::getReportHistoryBySale($idSale);
        if(!$sale){
            echo $this->response->error203("No se encuentra reporte para la venta:$idSale");
            die();
        }
        //Data en comun
        $saleID = $sale[0]['saleID'];
        $history = array();
        foreach($sale as $register){
        array_push( $history, array( "regNr" => $register['regNr'],"typereg" => $register['typeReg'],"status" => $register['status'],"employeeDoc" => $register['employeeDoc'],"employeeName" => $register['employeeName'],"date" => $register['date'],"comment" => $register['comment'],));
        }
        $response = array("saleID" => $saleID,"history" => $history);
        echo $this->response->successfully("Historial para la Venta:$idSale", $response);  
    }
    public function getSaleByStatus($status){
        $sale = SaleModel::getSalesByStatus($status);
        if(!$sale){
            echo $this->response->error203("No existen ventas para $status");
            die();
        }
        //Data en comun
        $name = $sale[0]['nameStatus'];
        //Data de cada venta en ese estado
        $sales = array();
        foreach($sale as $salesInState){
        array_push( $sales, array( "idSale" => $salesInState['idSale'],"docEmployee" => $salesInState['docEmployee'],"employeeName" => $salesInState['employeeName'],"lastUpdate" => $salesInState['lastUpdate'],"lastComment" => $salesInState['lastComment']));
        }
        $response = array("nameStatus" => $name, "sales" => $sales);
        echo $this->response->successfully("Ventas en estado $status:", $response);  
    }
    public function getAllSalesForDay($day){
        $sale = SaleModel::getAllSalesByDay($day);
        if(!$sale){
            echo $this->response->error203("No se encuentran ventas para el dia $day");
            die();
        }
        $sales = array();
        foreach($sale as $salesInDay){
            $balances =+ $salesInDay["total"];
            $isBusiness = $salesInDay["companyName"];
            if(!$isBusiness){
            array_push( $sales, array( "ID" => $salesInDay['ID'],"date" => $salesInDay['date'],"address" => $salesInDay['address'],"clientID" => $salesInDay['clientID'],"clientInfo" => $salesInDay['clientInfo'],"delivery" => $salesInDay['delivery'],"payment" => $salesInDay['payment'],"total" => $salesInDay['total']));
        }else{
            array_push( $sales, array( "ID" => $salesInDay['ID'],"date" => $salesInDay['date'],"address" => $salesInDay['address'],"clientID" => $salesInDay['clientID'],"companyName" => $salesInDay['companyName'],"companyRut" => $salesInDay['companyRut'],"clientInfo" => $salesInDay['clientInfo'],"delivery" => $salesInDay['delivery'],"payment" => $salesInDay['payment'],"total" => $salesInDay['total']));
        }
        
    }
        $totalSales = (count($sales));    
        $response = array("TotalSale" =>$totalSales,"balance"=>$balances, "sales" => $sales);
        echo $this->response->successfully("$totalSales Ventas obtenidas para la fecha:$day", $response);  
    
    }
    public function getAddresToCustomer($email){
        
        $mailExist = UserModel::getUserByEmail($email);
        if(!$mailExist){
            echo $this->response->error203("No se encuentra usuario para el correo $email");
            die();
        }        
        $sale = SaleModel::getCustomerAddressToSuggest($email);
        $suggestAddress = $sale['address'];
        if(!$suggestAddress){
            echo $this->response->error203("No hay direccion para sugerir");
            die();
        }
        
        echo $this->response->successfully("Direccion sugerida: $suggestAddress");  
    }
    
    //ACTUALIZAR
    public function updateReport($idSale,$saleData){
        $this->jwt->verifyTokenAndGetIdUserFromRequest(); 
        $bodyIsValid = $this->validateBodyOfReport($saleData);
        if(!$bodyIsValid){
             echo $this->response->error400('No se puede actualizar - Revise informacion');
            die();
        }
        $status = $saleData['status'];
        $employeeDoc = $saleData['employeeDoc'];
        $comment = $saleData['comment'];

        $saleExist = SaleModel::getSaleById($idSale);
        if(!$saleExist){
            echo $this->response->error203("Esta intentando editar una venta que no existe");
            die();
        }
        $employeeExist = EmployeeModel::getEmployeeByCi($employeeDoc);
        if(!$employeeExist){
            echo $this->response->error203("No existe empleado con documento $employeeDoc");
            die();
        }
        $statusExist = StatusModel::getStatusById($status);
        if(!$statusExist){
            echo $this->response->error203("No existe el estado $status");
            die();
        }
        $result = SaleModel::updateReportOfSale($idSale,$status,$employeeDoc,$comment);
        if(!$result){
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Reporte de venta $idSale actualizado con exito");
    }
        
        
        
        
        
        
    //ELIMINAR
}

?>
