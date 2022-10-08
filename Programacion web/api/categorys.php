<?php
include('./controllers/CategoryController.php');
header('Content-Type: application/json'); 
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

$response = new Response(); //Esta instancia va a ser utilizado a lo largo del controlador para las respuestas.
$category = new CategoryController();
$bodyOfRequest = file_get_contents('php://input'); //Obtiene el body de la request sin procesar(JSON).
$categoryData = json_decode($bodyOfRequest, 1); //Transforma el JSON en un array asosciativo.

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //Crear categoria
    $category->saveCategory($categoryData);

}else if($_SERVER['REQUEST_METHOD'] === 'GET'){

    if(isset($_GET['nameCategory'])){
        //Si manda este parametro seignifica que quiere obtener una categoria en particular
        $nameCategory = $_GET['nameCategory'];
        $category->getCategory($nameCategory);
        die();
    }
    $category->getCategorys();

}else if($_SERVER['REQUEST_METHOD'] === 'PUT'){
    if(!isset($_GET['idCategory'])){
        echo $response->error200("Error falta Id");    
    die();
    }    
    $idCategory = $_GET['idCategory'];
    $category->updateCategory($idCategory, $categoryData);    
    die();

}else if($_SERVER['REQUEST_METHOD'] === 'DELETE'){

    if(!isset($_GET['idCategory'])){
        //Si manda este parametro seignifica que quiere obtener una categoria en particular
        echo $response->error200("Error falta Id");    
        die();
    }
    $idSize = $_GET['idCategory'];
    $size->deleteCategory($idCategory);   

}else {
    echo $response->error200("Metodo no permitido");
}
?>