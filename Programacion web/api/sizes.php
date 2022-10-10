<?php
include('./controllers/SizeController.php');
header('Content-Type: application/json'); 
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

$response = new Response(); //Esta instancia va a ser utilizado a lo largo del controlador para las respuestas.
$size = new SizeController();
$bodyOfRequest = file_get_contents('php://input'); //Obtiene el body de la request sin procesar(JSON).
$sizeData = json_decode($bodyOfRequest, 1); //Transforma el JSON en un array asosciativo.

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //Crear talle
    $size->saveSize($sizeData);

}else if($_SERVER['REQUEST_METHOD'] === 'GET'){

    if(isset($_GET['nameSize'])){
        $nameSize = $_GET['nameSize'];
        $size->getSizeName($nameSize);
        die();
    }
    if(isset($_GET['idSize'])){
        $idSize = $_GET['idSize'];
        $size->getSizeId($idSize);
        die();
    }
    $size->getSizes();

}else if($_SERVER['REQUEST_METHOD'] === 'PUT'){
    //Editar talle
    if(!isset($_GET['idSize'])){
        echo $response->error203("Error falta Id");    
        die();
    }    
    $idSize = $_GET['idSize'];
    $size->updateSize($idSize, $sizeData);
    die();
    
}else if($_SERVER['REQUEST_METHOD'] === 'DELETE'){

    if(!isset($_GET['idSize'])){
        echo $response->error203("Error falta Id");    
        die();
    }
    $idSize = $_GET['idSize'];
    $size->deleteSize($idSize);
    die();
}else {
    echo $response->error203("Metodo no permitido");
}
?>