1.	Listar todos los productos disponibles para la venta, con su nombre, stock, precio.
SELECT p.name, s.name as talle, d.name as color ,p.stock, p.price, p.state
FROM product p, size s, design d
WHERE p.state = 1
AND p.product_design = d.id_design
AND p.product_size = s.id_size
AND p.stock > 0
2.	Listar las promociones vigentes con sus respectivos productos.
SELECT
pr.is_product as CodBarraPROMO,
p1.name as namePromo,
p1.stock as stockPromo,
pr.have_product as barcodeProd,
pr.quantity,
p2.id_product as ID,p2.name as Name_product,
s.name as size,
d.name as design
FROM promo pr, product p1, product p2 , design d, size s
WHERE p1.barcode = pr.is_product 
AND p2.barcode = pr.have_product 
AND d.id_design = p2.product_design 
AND s.id_size = p2.product_size
AND p1.state = 1
3.	Listar los pedidos que están pendientes de preparación.
SELECT 
r.sale_report AS idSale,
s.name AS nameStatus,
r.employee_report AS docEmployee,
concat_ws(' ', u.name , u.surname) AS employeeName,
date_format(r.date, '%d/%m/%Y %T') AS lastUpdate,
r.comment AS lastComment
FROM report r , status s, employee e, user u
WHERE r.status_report = s.id_status
AND s.name LIKE 'PENDIENTE'
AND r.employee_report = e.ci
AND e.employee_user = u.id_user
4.	Listar los 50 clientes que más compras han realizado.

5.	Listar los 20 productos más vendidos.

6.	Listar la cantidad de ventas realizadas por día.
SELECT
s.id_sale as ID,
s.date,
s.address,
s.user_purchase as clientID,
concat_ws(' ', c.company_name , c.rut_nr) AS companyInfo,
concat_ws(' ', u.name , u.surname) AS clientInfo,
d.name as delivery,
s.payment,
s.total
FROM sale s, delivery_time d, user u, customer c  
WHERE DATE like '2022%'
AND s.user_purchase = u.id_user
AND s.user_purchase = c.customer_user
AND s.sale_delivery = d.id_delivery'
7.	Listar por cliente el total de compras y la suma gastada. 