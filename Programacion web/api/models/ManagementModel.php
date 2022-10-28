<?php
    require_once("./database/Connection.php");
    class ManagementModel extends Connection {

        public static function getBalances(){
            $conecction = new Connection();
            $query = "SELECT 
            SUM(s.TOTAL) AS totalSale,
            SUM(sp.total) AS totalSupply
            FROM SALE s, supply sp";
            return $conecction->getData($query)->fetch_assoc();
        }
        public static function getBestClients($limit){
            $conecction = new Connection();
            $query = "SELECT 
            u.id_user AS idClient,
            concat_ws(' ', u.name , u.surname) AS clientInfo,
            c.company_name AS companyName,
            c.rut_nr AS companyRut,
            u.email AS mailClient,
            sum(s.total) AS spentMoney,
            count(id_sale) AS totalSales
            FROM sale s, user u, customer c 
            WHERE u.id_user = s.user_purchase 
            AND u.id_user = c.customer_user
            GROUP BY user_purchase 
            ORDER BY count(s.total) DESC 
            LIMIT $limit";
            return $conecction->getData($query)->fetch_all(MYSQLI_ASSOC);
        }

        
    }
        
?>