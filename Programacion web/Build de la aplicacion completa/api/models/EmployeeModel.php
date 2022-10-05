<?php
    require_once("./database/Connection.php");
    require_once("./models/UserModel.php");
    class EmployeeModel extends UserModel{

        private $rol;
        private $ci;

        function __construct($email, $name, $surname, $password, $rol, $ci){
            parent::__construct($email, $name, $surname, $password);
            $this->rol = $rol;
            $this->ci = $ci;
        }

        public static function getEmployeeByCi($ci){
            $conecction = new Connection();
            $query = "SELECT u.id_user, e.employee_role, e.ci, u.name, u.surname, u.email, u.address, u.phone
            FROM user u INNER JOIN employee e WHERE e.employee_user = u.id_user and ci = '$ci'";
            return $conecction->getData($query)->fetch_assoc();
        }

        public static function getEmployeeByIdUser($id){
            $conecction = new Connection();
            $query = "SELECT u.id_user, e.employee_role, e.ci, u.name, u.surname, u.email, u.address, u.phone
            FROM user u INNER JOIN employee e WHERE e.employee_user ='$id'";
            return $conecction->getData($query)->fetch_assoc();
        }

        public static function UpdateRoleEmployee($ci, $rol){
            $conecction = new Connection();
            $query = "UPDATE employee SET employee_role = '$rol' WHERE ci ='$ci'";
            return $conecction->setData($query);
        
        } 

        public static function DisableEmployee($ci){
            $conecction = new Connection();
            $query = "UPDATE employee SET state = 0 WHERE ci ='$ci'";
            return $conecction->setData($query);
        
        } 

        public function save(){
            $instanceMySql = $this->connection->getInstance();
            $instanceMySql->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            $result_transaccion = true;
            $userInsert = "INSERT INTO user(email, name, surname, password) VALUES ('$this->email', '$this->name', '$this->surname', '$this->password' )";
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