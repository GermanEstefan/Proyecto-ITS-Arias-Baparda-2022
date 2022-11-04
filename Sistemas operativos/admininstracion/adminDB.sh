#!/bin/bash
logger -p local1.info "Inicia el script de administracion de la base de datos"
#administracion de la base de datos
#variables
user=master
pass=1234
database=bindev
op=1
while [ $op != 0 ]
do
	clear
	echo "Menu de administracion de la base de datos"
	echo "··········································"
	echo "1) Crear usuario"
	echo "2) Eliminar usuario"
	echo "3) Administrar privilegios"
	echo "4) Actualizar cambios (flush privileges)"
	echo "0) Salir"
	echo ".........................................."
	echo "Seleccione una opcion.."
	read op
	if [[ $op =~ ^[0-9]+$ ]]
	then
	case $op in 
	1)
	logger -p local1.info "intentando crear un usuario"
	clear
	echo "Crear usuario"
	echo "............."
	echo "Ingrese nombre: "
	read newUser
	if [[ -n $newUser ]]
	then
		echo "Ingrese password: "
		read newPass
		if [[ -n $newPass ]]
		then
			sudo mysql -u "$user" -p"$pass" $database -e "CREATE USER '$newUser'@'%' IDENTIFIED BY '$newPass'"  
			if [ $? == 0 ]
			then
				logger -p local1.info "Usuario creado"
				echo "Usuario creado"
				echo "#################################"
				echo "para continuar presione enter"
				read exit
			else
				logger -p local1.info "No se pudo crear el usuario"
				echo "No se pudo crear el usuario"
				echo "#################################"
				echo "para continuar presione enter"
				read exit
			fi
		else
			logger -p local1.info "Se quiso ingresar una contraseña vacia"
			echo "No se admiten contraseñas vacias"
		fi
	else
		logger -p local1.info "Se quiso ingresar un nombre vacio"
		echo "No se admiten nombres vacios"
		sleep 2
	fi
	;;
	2)
	logger -p local1.info "se ingresa a la opcion de eliminar usuarios"
	echo "Eliminar usuario"
	echo "................"
	echo "Ingrese el nombre del usuario a eliminar"
	read delUser
	if [[ -z $delUser ]]
	then
		logger -p local1.info "Se quiso ingresar un nombre vacio"
		echo "No se aceptan valores vacios"
		sleep 2
	else
		mysql -u "$user" -p"$pass" $database -e "DROP USER $delUser"
		if [ $? == 0 ]
		then
			logger -p local1.info "usuario eliminado"
			echo "Usuario $delUser eliminado"
			sleep 2
		else
			logger -p local1.info "error al eliminar usuario"
			echo "El usuario que quiere eliminar no existe"
			sleep 2
		fi
	fi
	;;
	3)
	logger -p local1.info "se ingreso a la opcion de administrar privilegios"
	op3=1
	echo "Seleccionar el usuario al que se le van a administrar los privilegios"
	read userPriv
	mysql -u "$user" -p"$pass" mysql -e "SHOW GRANTS FOR $userPriv"
	if [ $? == 0 ]
	then
		while [ $op3 != 0 ]
		do
			clear
			echo "Menu de admin de privilegios"
			echo "----------------------------"
			echo "Usuario seleccionado:.."
			echo "- - - - - - - - - - - -"
			echo "$userPriv"
			echo "- - - - - - - - - - - -"
			echo "1) Ver privilegios"
			echo "2) Agregar privilegios"
			echo "3) Quitar privilegios"
			echo "0) Volver al menu principal"
			echo "----------------------------"
			echo "Seleccione una opcion"
			read op3
			case $op3 in 
			1)
			logger -p local1.info "..se ingreso a la opcion de ver privilegios"
			clear
			echo "--------------------------------------------"
			echo "--------------------------------------------"
			mysql -u "$user" -p"$pass" mysql -e "SHOW GRANTS FOR $userPriv" | grep "GRANT" | cut -f1-6 -d" "
			echo "--------------------------------------------"
			echo "Para volver presione enter"
			read exit
			;;
			2)
			logger -p local1.info "..se ingreso a la opcion de agregar privilegios"
			op2=1
			while [ $op2 != 0 ]
			do
			clear
			echo "Agregar privilegios"
			echo "-------------------------------------"
			echo "1)-- INSERT --"
			echo "2)-- SELECT --"
			echo "3)-- UPDATE --"
			echo "4)-- DELETE --"
			echo "0) Volver al menu anterior"
			echo "_____________________________________"
			echo "Ingrese una opcion"
			read op2
			case $op2 in
				1)
				logger -p local1.info "...intentando agregar privilegio INSERT"
				echo "Ingrese el nombre de la tabla donde quiera modificar los privilegios..."
				read table
				mysql -u "$user" -p"$pass" $database -e "GRANT INSERT ON `$database`.$table TO '$userPriv'@'%' "
				if [ $? == 0 ]
				then
					logger -p local1.info "...agregado privilegio INSERT a '$userPriv' en la tabla '$table'"
					clear
					echo "grant insert on user $userPriv"
					echo "Privilegio actualizado, No se olvide de actualizar los privilegios"
					echo "en el menu principal"
					sleep 2
				else
					logger -p local1.info "...no se pudo agregar el privilegio"
					echo "No se pudo aplicar el permiso"
					echo "Presione enter para continuar"
					read hhh
				fi
				;;
				2)
				logger -p local1.info "...intentando agregar privilegio SELECT"
				echo "Ingrese el nombre de la tabla donde quiera modificar los privilegios"
				read table
				mysql -u "$user" -p"$pass" $database -e "GRANT SELECT ON `$database`.$table TO $userPriv@'%'"
				if [ $? == 0 ]
				then
					logger -p local1.info "...agregado privilegio SELECT a '$userPriv' en la tabla '$table'"
					clear
					echo "grant select on user $userPriv"
					echo "Privilegio actualizado, No se olvide de actualizar los privilegios"
					echo "en el menu principal"
					sleep 2
				else
					logger -p local1.info "...no se pudo agregar el privilegio"
					echo "No se pudo aplicar el permiso"
					echo "Presione enter para continuar"
					read hhh
				fi
				;;
				3)
				logger -p local1.info "...intentando agregar privilegio UPDATE"
				echo "Ingrese el nombre de la tabla donde quiera modificar los privilegios"
				read table
				mysql -u "$user" -p"$pass" $database -e "GRANT UPDATE ON `$database`.$table TO $userPriv@'%'"
				if [ $? == 0 ]
				then
					logger -p local1.info "...agregado privilegio UPDATE a '$userPriv' en la tabla '$table'"
					clear
					echo "Privilegio actualizado, No se olvide de actualizar los privilegios"
					echo "en el menu principal"
					sleep 2
				else
					logger -p local1.info "...no se pudo agregar el privilegio"
					echo "No se pudo aplicar el permiso"
					echo "Presione enter para continuar"
					read hhh
				fi
				;;
				4)
				logger -p local1.info "...intentando agregar privilegio DELETE"
				echo "Ingrese el nombre de la tabla donde quiera modificar los privilegios"
				read table
				mysql -u "$user" -p"$pass" $database -e "GRANT DELETE ON `$database`.$table TO $userPriv@'%'"
				if [ $? == 0 ]
				then
					logger -p local1.info "...agregado privilegio DELETE a '$userPriv' en la tabla '$table'"
					clear
					echo "grant delete on user $userPriv"
					echo "Privilegio actualizado"
					sleep 2
				else
					logger -p local1.info "...no se pudo agregar el privilegio"
					echo "No se pudo aplicar el permiso"
					echo "Presione enter para continuar"
					read hhh
				fi
				;;
				0)
				logger -p local1.info "Saliendo del menu de agregar privilegios"
				clear
				;;
				*)
				echo "Opcion incorrecta"
				;;
			esac
			done
			;;
			3)
			logger -p local1.info "ingreso a la opcion de quitar privilegios"
			op33=1
			while [ $op33 != 0 ]
			do
			clear
			echo "Quitar privilegios"
			echo "-----------------------------------------"
			echo "1)--INSERT--"
			echo "2)--SELECT--"
			echo "3)--UPDATE--"
			echo "4)--DELETE--"
			echo "0) Volver al menu anterior"
			echo "_________________________________________"
			echo "Ingrese una opcion"
			read op33
			case $op33 in
				1)
				logger -p local1.info "intentando quitar INSERT de privilegios"
				echo "Ingrese el nombre de la tabla donde quiera cambiar los privilegios"
				read table
				mysql -u "$user" -p"$pass" $database -e "REVOKE INSERT ON `$database`.$table FROM $userPriv@'%'"
				if [ $? == 0 ]
				then
					logger -p local1.info "...quitado privilegio INSERT a '$userPriv' en la tabla '$table'"
					clear
					echo "Permiso revocado"
					sleep 2
				else
					logger -p local1.info "...no se pudo quitar el privilegio"
					echo "No se pudo revocar el permiso"
					echo "Presione enter para continuar"
					read hhh
				fi
				;;
				2)
				logger -p local1.info "Intentando quitar SELECT de privilegios"
				echo "Ingrese el nombre de la tabla donde quiera cambiar los privilegios"
				read table
				mysql -u "$user" -p"$pass" $database -e "REVOKE SELECT ON `$database`.$table FROM $userPriv@'%'"
				if [ $? == 0 ]
				then
					logger -p local1.info "...quitando privilegio SELECT a '$userPriv' en la tabla '$table'"
					clear
					echo "Permiso revocado"
					sleep 2
				else
					logger -p local1.info "...no se pudo quitar el privilegio"
					echo "No se pudo revocar el permiso"
					echo "Presione enter para continuar"
					read hhh
				fi
				;;
				3)
				logger -p local1.info "intentando quitar UPDATE de privilegios"
				echo "Ingrese el nombre de la tabla donde quiera cambiar los privilegios"
				read table
				mysql -u "$user" -p"$pass" $database -e "REVOKE UPDATE ON `$database`.$table FROM $userPriv@'%'"
				if [ $? == 0 ]
				then
					logger -p local1.info "...quitando privilegio UPDATE a '$userPriv' en la tabla '$table'"
					clear
					echo "Permiso revocado"
					sleep 2
				else
					logger -p local1.info "...no se pudo quitar el privilegio"
					echo "No se pudo revocar el permiso"
					echo "Presione enter para continuar"
					read hhh
				fi
				;;
				4)
				logger -p local1.info "intentando quitar DELETE de privilegios"
				echo "ingrese el nombre de la tabla donde quiera cambiar los privilegios"
				read table
				mysql -u "$user" -p"$pass" $database -e "REVOKE DELETE ON `$database`.$table FROM $userPriv@'%'"
				if [ $? == 0 ]
				then
					logger -p local1.info "...quitando privilegio DELETE a '$userPriv' en la tabla '$table'"
					clear
					echo "Permiso revocado"
					sleep 2
				else
					logger -p local1.info "...no se pudo quitar el privilegio"
					echo "No se pudo revocar el permiso"
					echo "Presione enter para continuar"
					read hhh
				fi
				;;
				0)
				logger -p local1.info "Saliendo de la opcion de quitar privilegios"
				clear
				;;
				*)
				echo "Opcion incorrecta"
				;;
				esac
			done
			;;
			0)
			logger -p local1.info "Saliendo del menu de privilegios"
			clear
			;;
			*)
			echo "Seleccione una opcion valida"
			;;
			esac
		done
	else
	logger -p local1.info "El usuario ingresado no existe en el sistema, error"
	echo "El usuario $userPriv no existe en la base de datos"
	sleep 2
	fi		
	;;
	4)
	logger -p local1.info "entrando en opcion de actualizar privilegios"
	clear
	echo "Actualizando privilegios...."
	mysql -u "$user" -p"$pass" $database -e "FLUSH PRIVILEGES"
	if [ $? == 0 ]
	then
		logger -p local1.info "...privilegios actualizados"
		clear
		echo "Privilegios actualizados"
		sleep 2
	else
		logger -p local1.info "...no se pudieron actualizar los privilegios"
		clear
		echo "No se pudieron actualizar los privilegios"
		echo "Presione enter para continuar"
		read hhh
	fi
	;;
	0)
	logger -p local1.info "saliendo del script de admin de la Base de Datos"
	echo "bye"
	clear
	;;
	*)
	echo "Opcion incorrecta"
	;;
	esac
	else
		logger -p local1.info "error, se quiso ingresar un valor no numerico"
		echo "se quiso ingresar un valor que no es numerico"
		echo "presione enter para continuar"
		read exit
	fi
done

