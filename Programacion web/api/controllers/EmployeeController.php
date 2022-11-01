<?php

include_once("./controllers/UserController.php");
include_once('./helpers/Response.php');
include_once("./helpers/Token.php");
include_once("./models/EmployeeModel.php");
include_once("./models/UserModel.php");
include_once("./models/RoleModel.php");

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
    public function validateBodyOfUpdateEmployee($userData){
        if (!isset($userData['rol'])
        || !isset($userData['name']) 
        || !isset($userData['surname']) 
        || !isset($userData['password']) 
        || !isset($userData['phone']) 
        || !isset($userData['address'])){
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
            echo $this->response->error401("Rol no valido para relizar esta accion");
            die();
        }
        $bodyIsValid = $this->validateBodyOfRegisterEmployee($userData);
        if (!$bodyIsValid) {
            echo $this->response->error203('Error en los datos enviados');
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
            echo $this->response->error203("Ya existe el empleado $ci");
            die();
        }
        $employeeExistByEmail = UserModel::getUserByEmail($email);
        if($employeeExistByEmail){
            echo $this->response->error203("Ya existe un registro para $email");
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
            "rol" => $employeeRol,
            "ci" => $ci
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
    public function getInfoByidEmployee($idEmployee){
        $this->jwt->verifyTokenAndGetIdUserFromRequest();
        $employee = EmployeeModel::getRoleOfEmployeeById($idEmployee);
        $rolOfEmployee = $employee["employee_role"];
        if(!($rolOfEmployee == 'JEFE')){
            http_response_code(401);
            echo $this->response->error401("Usted no esta autorizado para relizar esta accion");
            die();
        }
        $dataEmployee = EmployeeModel::getEmployeeById($idEmployee);
        if(!$dataEmployee){
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Informacion obtenida", $dataEmployee);
     
    }

    //edit
    public function updateEmployee($idEmployee,$userData){
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
        $bodyIsValid = $this->validateBodyOfUpdateEmployee($userData);
        if (!$bodyIsValid) {
            http_response_code(400);
            echo $this->response->error400();
            die();
        }
        
        $nameEmployee = $userData['name'];
        $surnameEmployee = $userData['surname'];
        $passwordEmployee = $userData['password'];
        $rolEmployee = $userData['rol'];
        $phoneEmployee = $userData['phone'];
        $addressEmployee = $userData['address'];

        if (!(strlen($passwordEmployee) > 5)){
        echo $this->response->error203("La contraseña ingresada es muy corta");
        die();
        }
        $validateRole = RoleModel::getRoleByName($rolEmployee);
        if(!$validateRole){
            echo $this->response->error203("El rol ingresado NO existe");
            die();
        }

        $updateEmployee = EmployeeModel::updateEmployee($idEmployee,$nameEmployee,$surnameEmployee,$passwordEmployee,$rolEmployee,$phoneEmployee,$addressEmployee);
        if(!$updateEmployee){
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Empleado actualziado con exito");
    }
    

    //edit
    public function activeEmployee($idEmployee){
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
 
        $activeEmployee = EmployeeModel::activeEmployee($idEmployee);
        if(!$activeEmployee){
             echo $this->response->error203("No se puede activar el empleado $idEmployee");
             die();
         }
         echo $this->response->successfully("El empleado $idEmployee fue activado con exito");
    }
    //edit
    public function disableEmployee($idEmployee){
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
 
        $disableEmployee = EmployeeModel::disableEmployee($idEmployee);
        if(!$disableEmployee){
             echo $this->response->error203("No se puede desactivar al funcionario el empleado $idEmployee");
             die();
         }
         echo $this->response->successfully("El empleado $idEmployee fue desactivado con exito");
    }

}
