<?php
    require_once('./models/UserModel.php');
    class CustomerModel extends UserModel{

        private $company;
        private $nRut;  

        function __construct($email, $name, $surname, $phone, $password, $address, $company, $nRut){
            parent::__construct($email, $name, $surname, $phone, $password, $address);
            $this->company = $company;
            $this->nRut = $nRut;    
        }

        public static function getCustomerByEmail($email){
            $conecction = new Connection();
            $query = "SELECT * from customer WHERE email='$email'";
            return $conecction->getData($query)->fetch_assoc();
        }
        
        public function save(){
            $instanceMySql = $this->connection->getInstance();
            $instanceMySql->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            $result_transaccion = true;
            $userInsert = "INSERT INTO user(email, name, surname, phone, address, password) VALUES ('$this->email', '$this->name', '$this->surname', '$this->phone', '$this->address', '$this->password' )";
            $resultUserInsert = $instanceMySql->query($userInsert);
            if(!$resultUserInsert) $result_transaccion = false;
            $idGeneratedFromUserInsert = $instanceMySql->insert_id;  
            $customerInsert = "INSERT INTO customer(id_customer, email, company, nrut) VALUES ($idGeneratedFromUserInsert, '$this->email', '$this->company', '$this->nRut')";
            $resultCustomerInsert = $instanceMySql->query($customerInsert);
            if(!$resultCustomerInsert) $result_transaccion = false;
            if($result_transaccion){
                $instanceMySql->commit();
                return $idGeneratedFromUserInsert;
            }else{
                $instanceMySql->rollback();
                return false;
            }
        }

    }

?>