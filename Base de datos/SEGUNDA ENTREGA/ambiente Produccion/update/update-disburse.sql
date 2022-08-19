---------------------------------------------------EDIT DISBURSE
--CAMBIAR NOMBRE FORMA DE PAGO A PROVEDORES
update disburse
set name = 'new name'
where id_disburse = 1; 
--FIN

--CAMBIAR DESCRIPCION FORMA DE PAGO A PROVEDORES
update disburse
set description = 'new description'
where id_disburse = 1; 
--FIN

--CAMBIAR ESTADO FORMA DE PAGO A PROVEDORES
update disburse
set state = '0'
where id_disburse = 1; 
--FIN
