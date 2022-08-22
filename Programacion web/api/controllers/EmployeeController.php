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
        if (!$bodyOfRequest || !isset($userData['rol']) || !isset($userData['ci'])) {
            return false;
        } else {
            return $userData;
        }
    }

    public function registerEmployee($userData)
    {

        $bodyIsValid = $this->validateBodyOfRegisterEmployee($userData);
        if (!$bodyIsValid) {
            http_response_code(400);
            $this->response->error400();
            die();
        }

        //Verificacion del token.
        if (!isset(getallheaders()['access-token'])) {
            http_response_code(401);
            echo $this->response->error401("No hay un token presente");
            die();
        }

        //Si tiene un token, y es valido lo decodificamos y obtenemos su ID.
        $idOfUser = $this->jwt->verifyToken(getallheaders()['access-token'])->data->idUser;
        $employee = EmployeeModel::getEmployeeById($idOfUser);
        $rolOfEmployee = $employee['name_rol'];
        if(!($rolOfEmployee == 'JEFE')){
            http_response_code(401);
            echo $this->response->error401("Usted no esta autorizado para relizar esta accion");
            die();
        }

        $email = $userData['email'];
        $name = $userData['name'];
        $surname = $userData['surname'];
        $phone = $userData['phone'];
        $password = $userData['password'];
        $address = $userData['address'];
        $rol = $userData['rol'];
        $ci = $userData['ci'];

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

        $newEmployee = new EmployeeModel($email, $name, $surname, $phone, $password, $address, $rol, $ci);
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

        $employeeId = $employeeExistInDatabase['id_employe'];
        $userInDatabase = UserModel::getUserById($employeeId);
        $userInDatabasePassword = $userInDatabase['password'];
        if (!($password == $userInDatabasePassword)) {
            http_response_code(401);
            echo $this->response->error401('Credenciales incorrectas');
            die();
        }

        $userToken = $this->jwt->generateToken($employeeId);
        $bodyResponse = array(
            "token" => $userToken,
            "email" => $userInDatabase['email'],
            "name" => $userInDatabase['name'],
            "surname" => $userInDatabase['surname'],
            "phone" => $userInDatabase['phone'],
            "address" => $userInDatabase['address']
        );
        echo $this->response->successfully("Autenticacion realizada con exito", $bodyResponse);
        die();
    }

    public function deleteEmployee($idOfEmployee){
        //En proceso...
    }

}
