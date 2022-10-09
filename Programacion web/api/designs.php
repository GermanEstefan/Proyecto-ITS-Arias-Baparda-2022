<?php
include('./controllers/DesignController.php');
header('Content-Type: application/json'); 
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

$response = new Response(); //Esta instancia va a ser utilizado a lo largo del controlador para las respuestas.
$design = new DesignController();
$bodyOfRequest = file_get_contents('php://input'); //Obtiene el body de la request sin procesar(JSON).
$designData = json_decode($bodyOfRequest, 1); //Transforma el JSON en un array asosciativo.

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //Crear talle
    $design->saveDesign($designData);

}else if($_SERVER['REQUEST_METHOD'] === 'GET'){

    if(isset($_GET['nameDesign'])){
        $nameDesign = $_GET['nameDesign'];
        $design->getDesignName($nameDesign);
        die();
    }else if(isset($_GET['idDesign'])){
        $idDesign = $_GET['idDesign'];
        $design->getDesignId($idDesign);
        die();
    }
    $design->getDesigns();

}else if($_SERVER['REQUEST_METHOD'] === 'PUT'){
    //Editar categoria
    if(isset($_GET['idDesign'])){
        $idDesign = $_GET['idDesign'];
        $design->updateDesign($idDesign, $designData);    
        die();
    }
    
}else if($_SERVER['REQUEST_METHOD'] === 'DELETE'){

    if(!isset($_GET['idDesign'])){
        //Si manda este parametro seignifica que quiere obtener una categoria en particular
        echo $response->error200("Error falta Id");    
        die();
    }
    $idDesign = $_GET['idDesign'];
    $design->deleteDesign($idDesign);
}else {
    echo $response->error200("Metodo no permitido");
}
?>