<?php
    require_once("./helpers/Response.php");
    require_once("./database/Connection.php");
    require_once('./models/UserModel.php');
    class CustomerModel extends UserModel{

        private $company;
        private $nRut;  

        function __construct($email, $name, $surname, $password, $company, $nRut){
            parent::__construct($email, $name, $surname, $password, "", "");
            $this->company = $company;
            $this->nRut = $nRut;    
        }

        public static function getCustomerByEmail($email){
            $conecction = new Connection();
            $query = "SELECT c.customer_user,c.company_name, c.rut_nr,u.email, u.name,u.surname,u.address,u.phone,u.state from customer c inner join user u on c.customer_user = u.id_user and u.email = '$email'";
            return $conecction->getData($query)->fetch_assoc();
        }
        public static function getInfoClientBySale($idSale){
            $conecction = new Connection();
            $query = "SELECT 
            concat_ws(' ', u.name , u.surname) AS infoClient,
            u.email AS mail,
            c.company_name AS infoCompany
            FROM sale s, customer c, user u  
            WHERE s.id_sale = $idSale
            AND s.user_purchase = c.customer_user
            AND s.user_purchase = u.id_user";
            return $conecction->getData($query)->fetch_assoc();
        }

        public static function getCustomerByRut($nRut){
            $conecction = new Connection();
            $query = "SELECT c.customer_user,c.company_name, c.rut_nr,u.email, u.name,u.surname,u.address,u.phone,u.state from customer c inner join user u on c.customer_user = u.id_user and c.rut_nr = '$nRut'";
            return $conecction->getData($query)->fetch_assoc();
        }
        public static function getPassOfUser($idUser){
            $conecction = new Connection();
            $query = "SELECT u.password AS pass FROM user u WHERE u.id_user = $idUser";
            return $conecction->getData($query)->fetch_assoc();
        }
        
        public static function getAllCustomers(){
            $conecction = new Connection();
            $query = "SELECT c.customer_user,c.company_name, c.rut_nr,u.email, u.name,u.surname,u.address,u.phone,u.state from customer c inner join user u ";
            return $conecction->getData($query)->fetch_assoc();
        }

        public static function updateCustomer($company,$nRut){
            $conecction = new Connection();
            $query = "UPDATE customer SET company_name = '$company', rut_nr= '$nRut' where rut_nr = '$nRut'";
            return $conecction->getData($query)->fetch_assoc();
        }

        public function save(){
            $conecction = new Connection();
            $instanceMySql = $conecction->getInstance();
            $instanceMySql->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            $result_transaccion = true;
            $queryOfUser = "INSERT INTO user(email, name, surname, password) VALUES ('$this->email', '$this->name', '$this->surname', '$this->password' )";
            $resultOfQueryUser = $instanceMySql->query($queryOfUser);
            if(!$resultOfQueryUser)  $result_transaccion = false;
            $idOfUserGenerated = $instanceMySql->insert_id;
            $queryOfCustomer = "UPDATE customer SET company_name = '$this->company', rut_nr =  '$this->nRut' WHERE customer_user = $idOfUserGenerated";
            $resultOfQueryCustomer = $instanceMySql->query($queryOfCustomer);
            if(!$resultOfQueryCustomer) $result_transaccion = false;
            if($result_transaccion){
                $instanceMySql->commit();
                return $idOfUserGenerated;
            }else{
                $instanceMySql->rollback();
                return false;
            }
        }
    }

?>