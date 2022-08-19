---------------------------------------------------EDIT CUSTOMER
--CAMBIAR NOMBRE DE EMPRESA
update customer
set company_name = 'New Company name'
where customer_user = 5000; 
--FIN

--CAMBIAR RUT EMPRESA 
update customer
set rut_nr = 'new rut nr'
where customer_user = 5000; 
--FIN

