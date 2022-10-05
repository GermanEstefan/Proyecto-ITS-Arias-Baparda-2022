<?php
    require_once('./models/UserModel.php');
    class CustomerModel extends UserModel{

        private $company;
        private $nRut;  

        function __construct($email, $name, $surname, $password, $company, $nRut){
            parent::__construct($email, $name, $surname, $password);
            $this->company = $company;
            $this->nRut = $nRut;    
        }

        public static function getCustomerByEmail($email){
            $conecction = new Connection();
            $query = "SELECT u.id_user, u.name, u.surname, u.email, u.address, u.phone , c.company_name, c.rut_nr
            FROM user u INNER JOIN customer c WHERE c.customer_user = u.id_user and u.email = '$email'";
            return $conecction->getData($query)->fetch_assoc();
        }

        public static function getCustomerByRut($nRut){
            $conecction = new Connection();
            $query = "SELECT u.id_user, u.name, u.surname, u.email, u.address, u.phone , c.company_name, c.rut_nr
            FROM user u INNER JOIN customer c WHERE c.rut_nr='$nRut'";
            return $conecction->getData($query)->fetch_assoc();
        }
        
        public function save(){
            $instanceMySql = $this->connection->getInstance();
            $instanceMySql->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            $result_transaccion = true;
            $queryOfUser = "INSERT INTO user(email, name, surname, password) VALUES ('$this->email', '$this->name', '$this->surname', '$this->password' )";
            $resultOfQueryUser = $instanceMySql->query($queryOfUser);
            if(!$resultOfQueryUser)  $result_transaccion = false;
            $idOfUserGenerated = $instanceMySql->insert_id;
            $queryOfCustomer = "UPDATE customer SET company_name = '$this->company', rut_nr =  $this->nRut WHERE customer_user = $idOfUserGenerated";
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