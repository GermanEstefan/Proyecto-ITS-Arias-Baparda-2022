<?php
include('./controllers/ManagementController.php');
header('Content-Type: application/json'); 
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

$response = new Response(); //Esta instancia va a ser utilizado a lo largo del controlador para las respuestas.
$managment = new ManagementController();
$bodyOfRequest = file_get_contents('php://input'); //Obtiene el body de la request sin procesar(JSON).
$managmenData = json_decode($bodyOfRequest, 1); //Transforma el JSON en un array asosciativo.

if($_SERVER['REQUEST_METHOD'] === 'GET'){
    //OBTENER BALANCE DE LA EMPRESA (PERDIDAS - GANANCIAS)
    if(isset($_GET['balance'])){
        $managment->getBalances();
        die();
    }
    //OBTENER LOS MEJORES CLIENTES (SEGUN CANTIDAD DE COMPRAS)
    if(isset($_GET['bestClients'])){
        $limit = $_GET['bestClients'];
        $managment->getBestClients($limit);
        die();
    }
    //OBTENER LOS PRODUCTOS MAS VENDIDOS 
    if(isset($_GET['bestProducts'])){
        $limit = $_GET['bestProducts'];
        $managment->getBestProducts($limit);
        die();
    }
   
}else {
    echo $response->error200("Metodo no permitido");
}
?>