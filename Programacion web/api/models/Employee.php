<?php
    require_once("./database/Connection.php");
    class Employee extends User{

        private $rol;
        private $ci;

        function __construct($email, $name, $surname, $phone, $password, $address, $rol, $ci){
            parent::__construct($email, $name, $surname, $phone, $password, $address);
            $this->rol = $rol;
            $this->ci = $ci;
        }

        public static function getEmployeeByCi($ci){
            $conecction = new Connection();
            $query = "SELECT * from employee WHERE ci='$ci'";
            return $conecction->getData($query)->fetch_assoc();
        }

        public static function getEmployeeById($id){
            $conecction = new Connection();
            $query = "SELECT * from employee WHERE id_employe='$id'";
            return $conecction->getData($query)->fetch_assoc();
        }

        public function save(){
            $instanceMySql = $this->connection->getInstance();
            $instanceMySql->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            $result_transaccion = true;
            $userInsert = "INSERT INTO user(email, name, surname, phone, address, password) VALUES ('$this->email', '$this->name', '$this->surname', '$this->phone', '$this->address', '$this->password' )";
            $resultUserInsert = $instanceMySql->query($userInsert);
            if(!$resultUserInsert)  $result_transaccion = false;
            $idGeneratedFromUserInsert = $instanceMySql->insert_id;
            $employeeInsert = "INSERT INTO employee(id_employe, name_rol, ci) VALUES ($idGeneratedFromUserInsert, '$this->rol', $this->ci)";
            $resultEmployeeInsert = $instanceMySql->query($employeeInsert);
            if(!$resultEmployeeInsert) $result_transaccion = false;
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