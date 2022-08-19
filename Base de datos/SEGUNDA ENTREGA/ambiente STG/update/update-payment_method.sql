---------------------------------------------------EDIT PAYMENT MEHOD
--CAMBIAR NOMBRE DE FORMA DE PAGO PARA CLIENTES
update payment_method
set name = 'New name'
where id_pay_meth = 1; 
--FIN

--CAMBIAR DESCRIPCION DE FORMA DE PAGO PARA CLIENTES
update payment_method
set description = 'new description'
where id_pay_meth = 1; 
--FIN

--CAMBIAR ESTADO DE FORMA DE PAGO PARA CLIENES
update payment_method
set state = '0'
where id_pay_meth = 1; 
--FIN
