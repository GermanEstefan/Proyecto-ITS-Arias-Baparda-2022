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
mejorar la idea de un select * from report where status_report = pendiente o venta confirmada
4.	Listar los 50 clientes que más compras han realizado.

5.	Listar los 20 productos más vendidos.
6.	Listar la cantidad de ventas realizadas por día.
7.	Listar por cliente el total de compras y la suma gastada. 