<?php
include('./controllers/RoleController.php');
header('Content-Type: application/json'); 
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

$response = new Response(); //Esta instancia va a ser utilizado a lo largo del controlador para las respuestas.
$role = new RoleController();
$bodyOfRequest = file_get_contents('php://input'); //Obtiene el body de la request sin procesar(JSON).
$roleData = json_decode($bodyOfRequest, 1); //Transforma el JSON en un array asosciativo.

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //Crear talle
    $role->saveRole($roleData);

}else if($_SERVER['REQUEST_METHOD'] === 'GET'){

    if(isset($_GET['nameRole'])){
        $nameRole = $_GET['nameRole'];
        $role->getRoleName($nameRole);
        die();
    }
    $role->getRoles();

}else if($_SERVER['REQUEST_METHOD'] === 'PATCH'){
    //Editar talle
    if(!isset($_GET['nameRole'])){
        echo $response->error203("Error falta nombre de rol");    
        die();
    }    
    $nameRole = $_GET['nameRole'];
    $role->updateRole($nameRole, $roleData);
    die();
    
}else if($_SERVER['REQUEST_METHOD'] === 'DELETE'){

    if(!isset($_GET['idRole'])){
        echo $response->error203("Error falta Id");    
        die();
    }
    $idRole = $_GET['idRole'];
    $role->deleteRole($idRole);
    die();
}else {
    echo $response->error203("Metodo no permitido");
}
?>