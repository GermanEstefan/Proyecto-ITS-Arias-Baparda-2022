<?php
    require_once("./database/Connection.php");
    require_once("./models/UserModel.php");
    class EmployeeModel extends UserModel{

        private $rol;
        private $ci;

        function __construct($email, $name, $surname, $password, $rol, $ci, $phone, $address){
            parent::__construct($email, $name, $surname, $password, $phone, $address);
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
            $query = "SELECT * from employee WHERE employee_user='$id'";
            return $conecction->getData($query)->fetch_assoc();
        }

        public static function getEmployees(){
            $conecction = new Connection();
            $query = "SELECT * from employee";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }

        public function save(){
            $instanceMySql = parent::$conecction->getInstance();
            $instanceMySql->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            $result_transaccion = true;
            $userInsert = "INSERT INTO user(email, name, surname, password, phone, address) VALUES ('$this->email', '$this->name', '$this->surname', '$this->password', '$this->phone', '$this->address' )";
            $resultUserInsert = $instanceMySql->query($userInsert);
            if(!$resultUserInsert)  $result_transaccion = false;
            $idGeneratedFromUserInsert = $instanceMySql->insert_id;
            $employeeInsert = "INSERT INTO employee(employee_user, employee_role, ci) VALUES ($idGeneratedFromUserInsert, '$this->rol', $this->ci)";
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