---------------------------------------------------EDIT EMPLOYEE
--CAMBIAR ROL DEL EMPLEADO
update employee
set name_role = 'New Role'
where ci = 46858631; 
--FIN

--CAMBIAR ESTADO DEL EMPLEADO
update employee
set state = '0'
where ci = 46858631;
--FIN
