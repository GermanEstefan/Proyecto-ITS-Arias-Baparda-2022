<?php

include_once("./controllers/UserController.php");
include_once('./helpers/Response.php');
include_once("./helpers/Token.php");
include_once("./models/EmployeeModel.php");
include_once("./models/UserModel.php");

class EmployeeController{

    private $response;
    private $jwt;
    function __construct()
    {
        $this->response = new Response();
        $this->jwt = new Token();
    }

    public function validateBodyOfRegisterEmployee($userData){
        $bodyOfRequest = UserController::validateBodyOfRegisterUser($userData);
        if (!$bodyOfRequest || !isset($userData['rol']) || !isset($userData['ci']) ||  !isset($userData['address']) || !isset($userData['phone']) ) {
            return false;
        } else {
            return $userData;
        }
    }

    public function registerEmployee($userData)
    {
        //Verificamos el token y si es valido, obtenemos el id de usuario.
        $idOfUser = $this->jwt->verifyTokenAndGetIdUserFromRequest();
        if(!$idOfUser){
            echo $this->response->error401("no esta autorizado para relizar esta accion");
            die();    
        }
        $employee = EmployeeModel::getRoleOfEmployeeById($idOfUser);        
        $rolOfEmployee = $employee['employee_role'];
        if(!($rolOfEmployee == 'JEFE')){
            http_response_code(401);
            echo $this->response->error401("Usted no esta autorizado para relizar esta accion");
            die();
        }
        $bodyIsValid = $this->validateBodyOfRegisterEmployee($userData);
        if (!$bodyIsValid) {
            http_response_code(400);
            echo $this->response->error400();
            die();
        }
        $email = $userData['email'];
        $name = $userData['name'];
        $surname = $userData['surname'];
        $password = $userData['password'];
        $rol = $userData['rol'];
        $ci = $userData['ci'];
        $phone = $userData['phone'];
        $address = $userData['address'];

        $employeeExist = EmployeeModel::getEmployeeByCi($ci);
        if ($employeeExist) {
            http_response_code(200);
            echo $this->response->error200("Ya existe un empleado ingresado con la CI: " . $employeeExist['ci']);
            die();
        }
        $employeeExistByEmail = UserModel::getUserByEmail($email);
        if($employeeExistByEmail){
            http_response_code(200);
            echo $this->response->error200("Ya existe un empleado ingresado con el email: " . $employeeExistByEmail['email']);
            die();
        }
        
        $newEmployee = new EmployeeModel($email, $name, $surname, $password, $rol, $ci, $phone, $address);
        $resultOfSave = $newEmployee->save();
        
        if ($resultOfSave) {
            http_response_code(200);
            echo $this->response->successfully("Empleado dado de alta con exito");
        } else { 
            http_response_code(500);
            echo $this->response->error500();
        }
    }

    public function loginEmployee($userData){

        if (!isset($userData['ci']) || !isset($userData['password'])) {
            http_response_code(400);
            echo $this->response->error400();
            die();
        }

        $ci = $userData['ci'];
        $password = $userData['password'];

        $employeeExistInDatabase = EmployeeModel::getEmployeeByCi($ci);
        if (!$employeeExistInDatabase) {
            http_response_code(200);
            echo $this->response->error200("El empleado con la ci: $ci no existe");
            die();
        }

        $employeeState = $employeeExistInDatabase['state'];
        if($employeeState == 0){
            http_response_code(401);
            echo $this->response->error401("El empleado con la ci: $ci se encuentra dado de baja");
            die();
        }

        $employeeRol = $employeeExistInDatabase['employee_role'];
        $employeeId = $employeeExistInDatabase['employee_user'];
        $userInDatabase = UserModel::getUserById($employeeId);
        $userInDatabasePassword = $userInDatabase['password'];
        if (!($password == $userInDatabasePassword)) {
            http_response_code(401);
            echo $this->response->error401('Credenciales incorrectas');
            die();
        }

        $userToken = $this->jwt->generateToken($employeeId, $employeeRol);
        $bodyResponse = array(
            "token" => $userToken,
            "email" => $userInDatabase['email'],
            "name" => $userInDatabase['name'],
            "surname" => $userInDatabase['surname'],
            "rol" => $employeeRol
        );
        echo $this->response->successfully("Autenticacion realizada con exito", $bodyResponse);
        die();
    }

    public function getEmployees(){
        $idOfUser = $this->jwt->verifyTokenAndGetIdUserFromRequest();
        $employee = EmployeeModel::getRoleOfEmployeeById($idOfUser);
        $rolOfEmployee = $employee['employee_role'];
        if(!($rolOfEmployee == 'JEFE')){
            http_response_code(401);
            echo $this->response->error401("Usted no esta autorizado para relizar esta accion");
            die();
        }
        $employees = EmployeeModel::getEmployees();
        echo json_encode($employees); 
    }

    public function deleteEmployee($idOfEmployee){
        //En proceso...
    }

}
