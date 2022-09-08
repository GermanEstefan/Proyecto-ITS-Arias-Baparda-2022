<?php

include_once('./helpers/Response.php');
include_once("./helpers/Token.php");
include_once("./models/CustomerModel.php");
include_once("./controllers/UserController.php");
include_once("./models/UserModel.php");


class CustomerController
{
   
    private $response;
    private $jwt;
    function __construct()
    {
        $this->response = new Response();
        $this->jwt = new Token();
    }

    private function validateBodyOfRegisterCustomer($userData)
    {
        $bodyOfRequest = UserController::validateBodyOfRegisterUser($userData);

        if(!$bodyOfRequest || !isset($userData['type'])){
            return false;
        }
        $typeOfUser = $userData['type'];
        if(!($typeOfUser === 'COMPANY' || $typeOfUser === 'NORMAL')){
            return false;
        }
        if($typeOfUser === 'COMPANY'){
            if( !isset($bodyOfRequest['company']) || !isset($bodyOfRequest['nRut']) || empty($bodyOfRequest['company']) || empty($bodyOfRequest['nRut'])  ) return false;
        }
        return $userData;
    }

    public function registerCustomer($userData)
    {

        $bodyIsValid = $this->validateBodyOfRegisterCustomer($userData);
        if (!$bodyIsValid) {
            http_response_code(400);
            echo $this->response->error400();
            die();
        }

        $email = $userData['email'];
        $name = $userData['name'];
        $surname = $userData['surname'];
        $password = $userData['password'];
        $type = $userData['type'];

        $customerExist = UserModel::getUserByEmail($email);
        if ($customerExist) {
            http_response_code(200);
            echo $this->response->error200("Ya existe un cliente ingresado con el email: " . $customerExist['email']);
            die();
        }

        $idOfCustomerSaved = null;
        if($type === 'COMPANY'){
            //USUARIO TIPO EMPRESA
            $company = $userData['company'];
            $nRut = $userData['nRut'];
            $customerTypeCompanyExistByRut = CustomerModel::getCustomerByRut($nRut);
            if($customerTypeCompanyExistByRut){
                http_response_code(200);
                echo $this->response->error200("Ya existe una empresa ingreado con ese RUT: " . $userData['nRut']);
                die();
            }
            $newCustomerTypeCompany = new CustomerModel($email, $name, $surname, $password, $company, $nRut);
            $idOfCustomerSaved = $newCustomerTypeCompany->save();
        }else{
            //USUARIO TIPO NORMAL
            $newCustomerTypeNormal = new UserModel($email, $name, $surname, $password);
            $idOfCustomerSaved = $newCustomerTypeNormal->save();
        }
   
        if ($idOfCustomerSaved) {
            $userToken = $this->jwt->generateToken($idOfCustomerSaved);
            $bodyResponse = array(
                "token" => $userToken
            );
            http_response_code(200);
            echo $this->response->successfully("Registro realizado con exito", $bodyResponse);
        } else {
            http_response_code(500);
            echo $this->response->error500();
        }
    }

    public function loginCustomer($userData)
    {

        if (!isset($userData['email']) || !isset($userData['password'])) {
            http_response_code(400);
            echo $this->response->error400();
            die();
        }

        $email = $userData['email'];
        $password = $userData['password'];
        $customerExistInDatabase = UserModel::getUserByEmail($email);

        if (!$customerExistInDatabase) {
            http_response_code(200);
            echo $this->response->error200("El cliente con el email: $email no existe");
            die();
        }

        $customerInDatabasePassword = $customerExistInDatabase['password'];
        if (!($password == $customerInDatabasePassword)) {
            http_response_code(401);
            echo $this->response->error401('Credenciales incorrectas');
            die();
        }

        $customerInDatabaseState = $customerExistInDatabase['state'];
        if($customerInDatabaseState == 0){
            http_response_code(401);
            echo $this->response->error401("Este usuario se encuentra dado de baja, contacte con el administrador.");
            die();
        }

        $customerInDatabaseId = $customerExistInDatabase['id_user'];
        $userToken = $this->jwt->generateToken($customerInDatabaseId);
        $bodyResponse = array(
            "token" => $userToken,
            "email" => $customerExistInDatabase['email'],
            "name" => $customerExistInDatabase['name'],
            "surname" => $customerExistInDatabase['surname'],
            "phone" => $customerExistInDatabase['phone'],
            "address" => $customerExistInDatabase['address']
        );
        
        echo $this->response->successfully("Autenticacion realizada con exito", $bodyResponse);
        die();
    }
}
