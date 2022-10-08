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
        //Si manda este parametro seignifica que quiere obtener una categoria en particular
        $nameSize = $_GET['nameSize'];
        $size->getSize($nameSize);
        die();
    }
    $size->getSizes();

}else if($_SERVER['REQUEST_METHOD'] === 'PUT'){
    //Editar categoria
    if(isset($_GET['idSize'])){
        $idSize = $_GET['idSize'];
        $size->updateSize($idSize, $sizeData);    
        die();
    }
    
}else {
    echo $response->error200("Metodo no permitido");
}
?>