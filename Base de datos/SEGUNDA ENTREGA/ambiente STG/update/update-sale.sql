---------------------------------------------------EDIT SALE
--CAMBIAR DESPACHO DE LA VENTA
update sale
set sale_delivery = 'new delivery time'
where id_sale = 700000; 
--FIN

--CAMBIAR DIRECCION DE LA VENTA
update sale
set address = 'new address'
where id_sale = 700000; 
--FIN