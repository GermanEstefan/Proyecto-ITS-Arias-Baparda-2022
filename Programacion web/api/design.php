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
        $design->getDesign($nameDesign);
        die();
    }else if(isset($_GET['idDesing'])){
        $idDesign = $_GET['idDesign'];
        $design->getDesign($idDesign);
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
    
}else {
    echo $response->error200("Metodo no permitido");
}
?>