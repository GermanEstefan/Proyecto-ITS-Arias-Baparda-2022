<?php

include_once('./helpers/Response.php');
include_once("./helpers/Token.php");
include_once("./models/CustomerModel.php");
include_once("./controllers/UserController.php");
include_once("./models/UserModel.php");
include_once("./helpers/Mail.php");


class CustomerController
{
   
    private $response;
    private $jwt;
    function __construct()
    {
        $this->response = new Response();
        $this->jwt = new Token();
    }

    private function validateBodyOfRegisterCustomer($userData){
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
    private function validateBodyUpdatePassword($userData){
        if( !isset($userData['oldPassword']) 
        ||  !isset($userData['newPassword']))
        return false;
        //aca tenemos que validar mas cosas como que tenga un largo especifico (se pueden enviar nombre de ctegoria con valor " ")
        return $userData;
    }
    private function validateBodyDisableAccount($userData){
        if( !isset($userData['password']) 
        ||  !isset($userData['email']))
        return false;
        //aca tenemos que validar mas cosas como que tenga un largo especifico (se pueden enviar nombre de ctegoria con valor " ")
        return $userData;
    }
    private function validateBodyOfConsult($userData){
        if( !isset($userData['client']) 
        ||  !isset($userData['subject'])
        ||  !isset($userData['text']))
        return false;
        //aca tenemos que validar mas cosas como que tenga un largo especifico (se pueden enviar nombre de ctegoria con valor " ")
        return $userData;
    }



    public function consultCustomer($userData){
        $bodyOfRequest = CustomerController::validateBodyOfConsult($userData);
        if(!$bodyOfRequest){
            http_response_code(400);
            echo $this->response->error400;
            die();
        }
        $client = $userData['client'];
        $subject = $userData['subject'];
        $text = $userData['text'];
        Mail::getConsult($client,$subject,$text);
        echo $this->response->successfully("Consulta enviada con exito!");
    }




    public function updateCustomer($userData)
    {
        $idOfUserRequested = $this->jwt->verifyTokenAndGetIdUserFromRequest();
        $bodyOfRequest = UserController::validateBodyOfUpdateUser($userData);
        if(!$bodyOfRequest){
            http_response_code(400);
            echo $this->response->error203("Error en los datos enviados");
            die();
        }

        $name = $userData['name'];
        $surname = $userData['surname'];
        $address = $userData['address'];
        $phone = $userData['phone'];
        
        $result = UserModel::updateUser($idOfUserRequested, $name, $surname, $address, $phone);
        if(!$result){
            echo $this->response->error500();
            die();
        } 
        echo $this->response->successfully("Actualizacion realizada con exito");
    }
    public function updatePasswordOfCustomer($userData){
        $idOfUserRequested = $this->jwt->verifyTokenAndGetIdUserFromRequest();
        $bodyOfRequest = CustomerController::validateBodyUpdatePassword($userData);
        if(!$bodyOfRequest){
            http_response_code(400);
            echo $this->response->error203("Error en los datos enviados");
            die();
        }

        $old = $userData['oldPassword'];
        $new = $userData['newPassword'];
        if (strlen($old)<5 ){
            echo $this->response->error203("La contraseña debe tener 5 caracteres minimo");
            die();
        }
        if (strlen($new)<5 ){
            echo $this->response->error203("La contraseña debe tener 5 caracteres minimo");
            die();
        }
        $CustomerData = UserModel::getUserById($idOfUserRequested);
        $verifyPass = $CustomerData['password'];
        
        if (!(password_verify($old, $verifyPass))) {
            http_response_code(401);
            echo $this->response->error401('Credenciales incorrectas');
            die();
        }
        $setNewPass = UserModel::updatePassword($idOfUserRequested,$new);
        if(!$setNewPass){
            echo $this->response->error203("No se pudo actualizar la contraseña");
            die();
        }
        echo $this->response->successfully("Contraseña actualizada con exito");
    }
    public function updateStateOfCustomer($userData){
        
        $bodyOfRequest = CustomerController::validateBodyDisableAccount($userData);
        if(!$bodyOfRequest){
            http_response_code(400);
            echo $this->response->error400();
            die();
        }

        $password = $userData['password'];
        $email = $userData['email'];

        $CustomerData = UserModel::getUserByEmail($email);
        $verifyPass = $CustomerData['password'];
        
        if (!(password_verify($password, $verifyPass))) {
            http_response_code(401);
            echo $this->response->error401('Credenciales incorrectas');
            die();
        }

        $disableAccount = UserModel::disableUser($email);
        if(!$disableAccount){
            echo $this->response->error203("Error al eliminar cuenta");
            die();
        }
        echo $this->response->successfully("Su cuenta a sido eliminada con exito");
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
            $newCustomerTypeNormal = new UserModel($email, $name, $surname, $password, "", "");
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

    public function loginCustomer($userData){

        if (!isset($userData['email']) || !isset($userData['password'])) {
            http_response_code(400);
            echo $this->response->error400();
            die();
        }

        $email = $userData['email'];
        $password = $userData['password'];
        $CustomerData = UserModel::getUserByEmail($email);

        if (!$CustomerData) {
            http_response_code(200);
            echo $this->response->error200("EL $email NO SE ENCUENTRA REGISTRADO");
            die();
        }
        $verifyPass = $CustomerData['password'];
        
        if (!(password_verify($password, $verifyPass))) {
            http_response_code(401);
            echo $this->response->error401('Credenciales incorrectas');
            die();
        }

        $customerState = $CustomerData['state'];
        if($customerState != 1){
            http_response_code(401);
            echo $this->response->error401("Usuario se encuentra dado de baja");
            die();
        }

        $customerInDatabaseId = $CustomerData['id_user'];
        $userToken = $this->jwt->generateToken($customerInDatabaseId);
        $bodyResponse = array(
            "token" => $userToken,
            "email" => $CustomerData['email'],
            "name" => $CustomerData['name'],
            "surname" => $CustomerData['surname'],
            "phone" => $CustomerData['phone'],
            "address" => $CustomerData['address']
        );
        
        echo $this->response->successfully("Autenticacion realizada con exito", $bodyResponse);
        die();
    }
}
