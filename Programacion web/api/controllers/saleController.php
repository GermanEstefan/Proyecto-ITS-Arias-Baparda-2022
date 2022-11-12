<?php

include_once('./helpers/Response.php');
include_once("./helpers/Token.php");
include_once("./models/SaleModel.php");
include_once("./models/DeliveryModel.php");
include_once("./models/UserModel.php");
include_once("./models/EmployeeModel.php");
include_once("./models/StatusModel.php");
include_once("./models/ProductModel.php");
include_once("./helpers/Mail.php");
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
        ||  !isset($saleData['status']))
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
        
        $deliveryExist = DeliveryModel::getDeliveryById($delivery);
        if (!$deliveryExist) {
            echo $this->response->error203("El Horario indicado no es correcto");
            die();
        }
      
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
        
        $to = $client;
        $totalSale = 0;
        $products = array();
        foreach ($productsForSale as $product) {
            $barcode = $product['barcode'];
            $quantity = $product['quantity'];
            $getExtraInfo = ProductModel::getInfoProductForMail($barcode);
            $productName = $getExtraInfo['productName'];
            $price = $getExtraInfo['price'];
            $total = ($quantity * $price);
            $totalSale += $total;
            array_push($products, array("productName"=>$productName,"quantity"=> $quantity,"price"=> $price,"total"=>$total));
        }
        if ($payment === 0){
            $payment = 'Efectivo';
        }else{
            $payment = 'Pago Online';
        }
        $getTime = DeliveryModel::getDeliveryById($delivery);
        $time = $getTime['name'];
        $date = date("d/m/Y");
        if ($payment === 'Efectivo'){
            $status = 'PENDIENTE';
        }else{
            $status = 'CONFIRMADO';
        } 
        $infoExtra = array( "status" => $status,"payment" => $payment, "time" => $time, "address" => $address, "date" => $date, "totalSale" => $totalSale);
        Mail::sendInvoice($to, $products, $infoExtra);
        
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
        $saleID = $sale[0]['idSale'];
        $totalSale = $sale[0]['totalSale'];
        $dateSale = $sale[0]['saleDate'];
        $statusSale = $sale[0]['statusActual'];
        $clientInfo = array("clientMail" => $sale[0]['clientMail'],"clientName" => $sale[0]['clientName'],"companyName" => $sale[0]['nameCompany'],"clientPhone" => $sale[0]['clientPhone']);
        $saleInfo = array( "addressSale" => $sale[0]['addressSale'],"deliverySale" => $sale[0]['deliverySale'],"payment" => $sale[0]['payment']);
        $productSale = array();
        foreach($sale as $detail){
        array_push( $productSale, array( "barcode" => $detail['barcode'],"productName" => $detail['productName'],"quantity" => $detail['quantity'],"total" => $detail['total']));
        }
        $response = array("idSale"=>$saleID,"totalSale"=>$totalSale,"dateSale"=>$dateSale,"statusSale"=>$statusSale,"clientInfo"=>$clientInfo,"saleInfo"=>$saleInfo,"productSale"=>$productSale);
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
        array_push( $history, array( "regNr" => $register['regNr'],"typereg" => $register['typeReg'],"status" => $register['status'],"employeeDoc" => $register['employeeDoc'],"employeeName" => $register['employeeName'],"date" => $register['date'],"comment" => $register['comment']));
        }
        $response = array("saleID" => $saleID,"history" => $history);
        echo $this->response->successfully("Historial para la Venta:$idSale", $response);  
    }
    public function getReportHistoryByClient($idSale){
        $sale = SaleModel::getReportHistoryForClient($idSale);
        if(!$sale){
            echo $this->response->error203("No se encuentra reporte para la venta:$idSale");
            die();
        }
        //Data en comun
        $ID = $sale[0]['ID'];
        $actualStatus = $sale[0]['status'];
        $history = array();
        foreach($sale as $register){
        array_push( $history, array( "status" => $register['status'],"personalName" => $register['personalName'],"date" => $register['date'],"comment" => $register['comment']));
        }
        $response = array("ID" => $ID,"actualStatus" => $actualStatus,"history" => $history);
        echo $this->response->successfully("Historial de la Venta:$idSale", $response);  
    }
    public function getSaleByStatus($status){
        $sale = SaleModel::getSalesByStatus($status);
        //Data de cada venta en ese estado
        $sales = array();
        foreach($sale as $salesInState){
        $employeeIsSystem = $salesInState['employeeMail'];
        if($employeeIsSystem === '1 System Response') {
            $salesInState['employeeMail']= 'Resp. Automatica del Sistema';
        }
            array_push( $sales, array( "idSale" => $salesInState['idSale'],"nameStatus" => $salesInState['nameStatus'],"totalSale" => $salesInState['totalSale'],"employeeMail" => $salesInState['employeeMail'],"lastUpdate" => $salesInState['lastUpdate']));
        }
        echo $this->response->successfully("Ventas en estado $status:", $sales);  
    }
    public function getSalesForUser($email){
        $mailExist = UserModel::validEmailForSale($email);
        if (!$mailExist) {
            echo $this->response->error203("El mail no existe");
            die();
        }
        $analyzeMail = $mailExist["state"];
        if($analyzeMail == 0){
            echo $this->response->error203("El usuario se encuentra inactivo");
            die();
        }
        $identifyClient = SaleModel::getInfoCustomerByEmail($email);
        $idClient = ($identifyClient["id_user"]);

        $sale = SaleModel::getSalesForUserID($idClient);
        if(!$sale){
            echo $this->response->error203("Aun no compro nada");
            die();
        }
        $sales = array();
        foreach($sale as $salesHistory){
        array_push( $sales, array( "ID" => $salesHistory['ID'],"date" => $salesHistory['date'],"status" => $salesHistory['status'],"total" => $salesHistory['total']));
        }
        $response = array("sales" => $sales);
        echo $this->response->successfully("Historial de ventas", $response);  
    }
    public function getAllSalesForDay($day){
        $sale = SaleModel::getAllSalesByDay($day);
        if(!$sale){
            echo $this->response->error203("No se encuentran ventas para el dia $day");
            die();
        }
        $balances = 0;
        $sales = array();
        foreach($sale as $salesInDay){
            $balances += $salesInDay["total"];
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
    /*
    public function getSalesForRange($fromDay,$untilDay){
        $sale = SaleModel::getAllSalesByRange($fromDay,$untilDay);
        if(!$sale){
            echo $this->response->error203("No se encuentran ventas para el dia $day");
            die();
        }
        $balances = 0;
        $sales = array();
        foreach($sale as $salesInDay){
            $balances += $salesInDay["total"];
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
    */
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
    public function getSalesByDelivery($idDelivery){
        
        $deliveryExist = DeliveryModel::getDeliveryById($idDelivery);
        if (!$deliveryExist) {
            echo $this->response->error203("El Horario indicado no es correcto");
            die();
        }
        $sale = SaleModel::getSalesByIdDelivery($idDelivery);
        if(!$sale){
            echo $this->response->error203("No se encuentran ventas para el horario indicado");
            die();
        }
        //Data en comun
        $delivery = $sale[0]['delivery'];
        $sales = array();
        foreach($sale as $individualSale){
        array_push( $sales, array( "saleID" => $individualSale['saleID'],"saleDate" => $individualSale['saleDate'],"lastUpdate" => $individualSale['lastUpdate'],"client" => $individualSale['client'],"address" => $individualSale['address'],"phone" => $individualSale['phone'],));
        }
        $totalSales = (count($sales));
        $response = array("delivery" => $delivery,"sales" => $sales);
        echo $this->response->successfully("$totalSales Ventas para entregar en el horario $delivery", $response);  
    }
    
    //ACTUALIZAR
    public function updateReport($idSale,$saleData){
        $employeeRole = $this->jwt->verifyTokenAndValidateEmployeeUser();
        if(!$employeeRole){
            echo $this->response->error203("PERMISO DENEGADO");
            die();
        }
        if (! ($employeeRole === 'JEFE' || $employeeRole === 'VENDEDOR')) {
            http_response_code(401);
            echo $this->response->error401("Rol no valido para relizar esta accion");
            die();
        }
        $bodyIsValid = $this->validateBodyOfReport($saleData);
        if(!$bodyIsValid){
             echo $this->response->error400('No se puede actualizar - Revise informacion');
            die();
        }
        $status = $saleData['status'];
        $employeeDoc = $saleData['employeeDoc'];

        $statusActualIsCanceled = SaleModel::getSaleById($idSale);
        $statusActual = $statusActualIsCanceled['statusActual'];
        if($statusActual === 'CANCELADA'){
            echo $this->response->error203("LA VENTA SE ENCUENTRA CANCELADA");
            die();
        }
        $getName = StatusModel::getStatusById($status);
        $nameStatus = $getName['name'];
        if($statusActual !== 'PENDIENTE' && $nameStatus === 'PENDIENTE') {
            echo $this->response->error203("LA VENTA YA FUE CONFIRMADA");
            die();
        }
        if($nameStatus === $statusActual){
            echo $this->response->error203("LA VENTA YA SE ENCUENTRA EN ESTADO $nameStatus");
            die();
        }
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
        
        if($nameStatus === 'CANCELADA'){
            $getProducts = SaleModel::saleIsCanceled($idSale);
            foreach($getProducts as $individual){
            $barcode = $individual['barcode'];
            $quantity = $individual['quantity'];
            $reloadStock = ProductModel::updateMoreStockProductsOfPromo($barcode,$quantity);
            }
            if(!$reloadStock){
                echo $this->response->error203("Error al devolver el stock a productos");
                die();
            }
            $setZeroInSale = SaleModel::setTotalForCanceled($idSale);
            if(!$setZeroInSale){
                echo $this->response->error203("Error al setear sale");
                die();
            }    
        }
        $nowDate = date('Y-m-d H:i:s');
        $getName = StatusModel::getStatusById($status);
        $nameStatus = $getName['name'];
        $comment = "Empleado:$employeeDoc cambio la venta:$idSale de estado $statusActual a $nameStatus";
        $result = SaleModel::updateReportOfSale($idSale,$status,$employeeDoc,$comment, $nowDate);
        if(!$result){
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Reporte de venta $idSale actualizado con exito");
    }

}

?>
