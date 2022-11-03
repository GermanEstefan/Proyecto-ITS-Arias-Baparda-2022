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
            $query = "SELECT 
            e.employee_user,
            e.employee_role,
            e.ci,
            e.state,
            u.email, 
            u.password, 
            u.name,
            u.surname,
            u.address,
            u.phone 
            from 
            employee e 
            inner join user u on e.employee_user = u.id_user and e.employee_user != 5000 and e.employee_user = '$id'";
            return $conecction->getData($query)->fetch_assoc();
        }
        public static function getRoleEmployeeById($id){
            $conecction = new Connection();
            $query = "SELECT 
            e.employee_role
            FROM employee e
            WHERE e.employee_user = $id and e.employee_user != 5000 and e.state = 1";
            return $conecction->getData($query)->fetch_assoc();
        }
        public static function getEmployeesByRole($nameRole){
            $conecction = new Connection();
            $query = "SELECT e.employee_user as ID_EMPLEADO,e.employee_role AS ROL_ASIGNADO ,e.state AS ESTADO ,u.email, u.name AS NOMBRE,u.surname AS APELLIDO,u.address AS DIRECCION,u.phone AS TELEFONO from employee e inner join user u on e.employee_user = u.id_user and e.employee_role = '$nameRole'";
            return $conecction->getData($query)->fetch_assoc();
        }
        public static function getStateOfEmployee($employeeID){
            $conecction = new Connection();
            $query = "SELECT e.state from employee e WHERE e.employee_user = $employeeID and e.state = 1";
            return $conecction->getData($query)->fetch_assoc();
        }

        public static function getRoleOfEmployeeById($id){
            $conecction = new Connection();
            $query = "SELECT employee_role FROM employee WHERE employee_user = '$id'";
            return $conecction->getData($query)->fetch_assoc();
        }

        public static function getEmployees(){
            $conecction = new Connection();
            $query = "SELECT e.employee_user,e.employee_role,e.ci,e.state,u.email, u.name,u.surname,u.address,u.phone from employee e inner join user u on e.employee_user = u.id_user and e.employee_user != 5000";
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
            $employeeInsert = "INSERT INTO employee (ci, employee_user, employee_role) VALUES ($this->ci,$idGeneratedFromUserInsert, '$this->rol')";
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

        //transa para updatear
        public static function updateEmployee($idEmployee,$nameEmployee,$surnameEmployee,$passwordEmployee,$rolEmployee,$phoneEmployee,$addressEmployee){
            $conecction = new Connection;
            $instanceMySql = $conecction->getInstance();
            $instanceMySql->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
            $result_transaccion = true;
            $userUpdate = "UPDATE user SET name = '$nameEmployee', surname = '$surnameEmployee', password = '$passwordEmployee', phone = '$phoneEmployee', address = '$addressEmployee' WHERE id_user = $idEmployee";
            $resultUserUpdate = $instanceMySql->query($userUpdate);
            if(!$resultUserUpdate)  $result_transaccion = false;
            $employeeUpdate = "UPDATE employee SET employee_role = '$rolEmployee' WHERE employee_user = $idEmployee";
            $resultEmployeeUpdate = $instanceMySql->query($employeeUpdate);
            if(!$resultEmployeeUpdate) $result_transaccion = false;
            if($result_transaccion){
                $instanceMySql->commit();
                return true;
            }else{
                $instanceMySql->rollback();
                return false;
            }
        }
        public static function disableEmployee($idEmployee){
            $conecction = new Connection;
            $query = "UPDATE employee SET state = 0 WHERE employee_user = $idEmployee";
            return $conecction->setData($query);
        }
        public static function activeEmployee($idEmployee){
            $conecction = new Connection;
            $query = "UPDATE employee SET state = 1 WHERE employee_user = $idEmployee";
            return $conecction->setData($query);
        }

    }

?>