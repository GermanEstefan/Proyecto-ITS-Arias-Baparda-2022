---------------------------------------------------EDIT DELIVERY
--CAMBIAR NOMBRE DE DELIVERY
update delivery_time
set id_delivery = 'New name'
where name_role = 1; 
--FIN

--CAMBIAR DESCRIPCION DE DELIVERY
update delivery_time
set description = 'new description'
where id_delivery = 1; 
--FIN

--CAMBIAR ESTADO DE DELIVERY
update delivery_time
set state = '0'
where id_delivery = 1; 
--FIN
