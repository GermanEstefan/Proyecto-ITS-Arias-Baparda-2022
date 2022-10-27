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
            $query = "SELECT e.employee_user,e.employee_role,e.state,u.email, u.name,u.surname,u.address,u.phone from employee e inner join user u on e.employee_user = u.id_user and e.ci = '$ci'";
            return $conecction->getData($query)->fetch_assoc();
        }

        public static function getEmployeeById($id){
            $conecction = new Connection();
            $query = "SELECT e.employee_user,e.employee_role,e.ci,e.state,u.email, u.name,u.surname,u.address,u.phone from employee e inner join user u on e.employee_user = u.id_user and e.employee_user = '$id'";
            return $conecction->getData($query)->fetch_assoc();
        }
        public static function getEmployeesByRole($nameRole){
            $conecction = new Connection();
            $query = "SELECT e.employee_user as ID_EMPLEADO,e.employee_role AS ROL_ASIGNADO ,e.state AS ESTADO ,u.email, u.name AS NOMBRE,u.surname AS APELLIDO,u.address AS DIRECCION,u.phone AS TELEFONO from employee e inner join user u on e.employee_user = u.id_user and e.employee_role = '$nameRole'";
            return $conecction->getData($query)->fetch_assoc();
        }

        public static function getRoleOfEmployeeById($id){
            $conecction = new Connection();
            $query = "SELECT employee_role FROM employee WHERE employee_user = '$id'";
            return $conecction->getData($query)->fetch_assoc();
        }

        public static function getEmployees(){
            $conecction = new Connection();
            $query = "SELECT e.employee_user,e.employee_role,e.ci,e.state,u.email, u.name,u.surname,u.address,u.phone from employee e inner join user u on e.employee_user = u.id_user";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }

        public function save(){
            $instanceMySql = $this->conecction->getInstance();
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