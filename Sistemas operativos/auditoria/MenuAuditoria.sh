#!/bin/bash
#menu de auditoria
logger -p local1.info "Ingreso al menu de auditoria"
op=1
while [ $op != 0 ]
do
	clear
	echo "Menu de auditoria"
	echo "-------------------------------------"
	echo "1) ver quien se encuentra conectado"
	echo "2) ver los ultimos logs de acceso"
	echo "3) ver los intentos fallidos de acceso"
	echo "4) buscar logs por nombre de usuario"
	echo "5) buscar logs por fecha"
	echo "6) ver logs por cambios en el sistema"
	echo "7) ver los ultimos inicios de sesion"
	echo "8) ver los intentos fallidos de acceso al servidor de respaldos"
	echo "9) ver los logs de los menus de administracion"
	echo "0) Salir"
	read op
	if [[ $op =~ ^[0-9]+$ ]]
	then
	case $op in
	1)
	logger -p local1.info "opcion: ver quien se encuentra conectado"
	w
	echo "presione enter para continuar"
	read exit
	;;
	2)
	logger -p local1.indo "opcion: ver los ultimos logs de acceso"
	last -a | head
	echo "presione enter para continuar"
	read exit
	;;
	3)
	logger -p local1.info "opcion: ver los intentos fallidos de acceso"
	sudo lastb -a
	echo "presione enter para contnuar"
	read exit
	;;
	4)
	logger -p local1.info "opcion: ver accesos por nombre"
	clear
	echo "Ingrese un nombre de usuario para realizar la busqueda"
	read userName
	if [ -z $userName]
	then
		logger -p local1.info "error, se ingreso un nombre vacio"
		echo "Debe ingresar un nombre de usuario"
		echo "Presione enter para continuar"
		read exit
	else
		last $userName | less
		if [ $? == 0 ]
		then
			logger -p local1.info "se buscaron los accesos de '$userName' con exito"
			echo "Presione enter para continuar"
			read exit
		else
			logger -p local1.info "el usuario que se esta buscando - '$userName' - no existe en los registros de acceso"
			echo "El usuario no tiene registros"
			read exit
		fi
	fi
	;;
	5)
	logger -p local1.info "opcion: buscar por fecha"
	clear
	monthNumber=0
	month=""
	day=""
	echo "Seleccione el mes: 1-Ene 2-Feb 3-Mar 4-Abr 5-May 6-Jun 7-Jul 8-Ago 9-Sep 10-Oct 11-Nov 12-Dic"
	echo "--para ver los logs de un mes en particular ingrese 0 cuando se le pida el dia--"
	read monthNumber
	if [[ $monthNumber =~ ^[0-9]+$ ]]
	then
	case $monthNumber in
		1)
		logger -p local1.info "buscando en el mes de enero" 
		month="Jan"
      		echo "Mes: $month"
		echo "Ingrese el dia: "
		read day
		if [ -z $day ]
		then
			logger -p local1.info "error, no se admiten dias de fecha vacios"
			echo "No se admiten fechas vacias"
			echo "Presione enter para continuar"
			read exit
		else
			if (( $day >= 1 && $day <= 31 ))
			then
				last -a | grep "$month $day"
				if [ $? == 0 ]
				then
					logger -p local1.info "...buscando registros del '$day' de enero"
					echo "presione enter para continuar"
					read exit
				else
					logger -p local1.info "...no hay registros para la fecha '$day' de enero"
					echo "######################################"
					echo "--------No hay logs para mostrar------"
					echo "######################################"
					echo "Presione enter para continuar"
					read exit
				fi
			elif (( $day == 0 ))
			then
				last -a | grep "$month" | less
				if [ $? == 0 ]
				then
					logger -p local1.info "mostrando los registros del mes de Enero"
					echo "Presione enter para continuar"
					read exit
				else
					logger -p local1.info "no existen registros para el mes de enero"
					echo "#####################################"
					echo "---No hay registros para este mes---"
					echo "Mes: Enero"
					echo "Presione enter para continuar"
					read exit
				fi
			else
				logger -p local1.info "error, se ingreso una fecha invalida"
				echo "Error, fecha invalida"
				echo "presione enter para continuar"
				read exit
			fi
		fi
		;;
		2)
		logger -p local1.info "buscando en el mes de febrero"
 		month="Feb"
      		echo "Mes: $month"
		echo "Ingrese el dia: "
		read day
		if [ -z $day ]
		then
			logger -p local1.info "error, se ingresaron valores vacios"
			echo "No se admiten fechas vacias"
			echo "Presione enter para continuar"
			read exit
		else
			if (( $day >= 1 && $day <= 31 ))
			then
				last -a | grep "$month $day"
				if [ $? == 0 ]
				then
					logger -p local1.info "mostrando los registros de febrero, '$day'"
					echo "presione enter para continuar"
					read exit
				else
					logger -p local1.info "no existen registros para la fecha solicitada"
					echo "######################################"
					echo "--------No hay logs para mostrar------"
					echo "######################################"
					echo "Presione enter para continuar"
					read exit
				fi
			elif (( $day == 0 ))
			then
				last -a | grep "$month" | less
				if [ $? == 0 ]
				then
					logger -p local1.info "mostrando registros del mes de febrero"
					echo "Presione enter para continuar"
					read exit
				else
					logger -p local1.info "no hay registros para el mes solicitado"
					echo "#####################################"
					echo "---No hay registros para este mes---"
					echo "Mes: Febrero"
					echo "Presione enter para continuar"
					read exit
				fi
			else
				logger -p local1.info "error, se ingreso una fecha invalida"
				echo "Error, fecha invalida"
				echo "presione enter para continuar"
				read exit
			fi
		fi
		;;
		3) 
		logger -p local1.info "buscando registros del mes de marzo"
		month="Mar"
      		echo "Mes: $month"
		echo "Ingrese el dia: "
		read day
		if [ -z $day ]
		then
			logger -p local1.info "error, no se admiten campos vacios"
			echo "No se admiten fechas vacias"
			echo "Presione enter para continuar"
			read exit
		else
			if (( $day >= 1 && $day <= 31 ))
			then
				last -a | grep "$month $day"
				if [ $? == 0 ]
				then
					logger -p local1.info "mostrando registros del '$day' de marzo"
					echo "presione enter para continuar"
					read exit
				else
					logger -p local1.info "no hay registros para la fecha ingresada"
					echo "######################################"
					echo "--------No hay logs para mostrar------"
					echo "######################################"
					echo "Presione enter para continuar"
					read exit
				fi
			elif (( $day == 0 ))
			then
				last -a | grep "$month" | less
				if [ $? == 0 ]
				then
					logger -p local1.info "mostrando todos los registros del mes de marzo"
					echo "Presione enter para continuar"
					read exit
				else
					logger -p local1.info "no hay registros disponibles"
					echo "#####################################"
					echo "---No hay registros para este mes---"
					echo "Mes: Marzo"
					echo "Presione enter para continuar"
					read exit
				fi
			else
				logger -p local1.info "error, la fecha que ingreso es invalida"
				echo "Error, fecha invalida"
				echo "presione enter para continuar"
				read exit
			fi
		fi
		;;
		4) 
		logger -p local1.info "buscando registros para el mes de abril"
		month="Apr"
      		echo "Mes: $month"
		echo "Ingrese el dia: "
		read day
		if [ -z $day ]
		then
			logger -p local1.info "error, no se admiten campos vacios"
			echo "No se admiten fechas vacias"
			echo "Presione enter para continuar"
			read exit
		else
			if (( $day >= 1 && $day <= 31 ))
			then
				last -a | grep "$month $day"
				if [ $? == 0 ]
				then
					logger -p local1.info "mostrando registros del '$day' de Abril"
					echo "presione enter para continuar"
					read exit
				else
					logger -p local1.info "no hay registros para la fecha indicada"
					echo "######################################"
					echo "--------No hay logs para mostrar------"
					echo "######################################"
					echo "Presione enter para continuar"
					read exit
				fi
			elif (( $day == 0 ))
			then
				last -a | grep "$month" | less
				if [ $? == 0 ]
				then
					logger -p local1.info "mostrando registros del mes de Abril"
					echo "Presione enter para continuar"
					read exit
				else
					logger -p local1.info "no hay registros diponibles"
					echo "#####################################"
					echo "---No hay registros para este mes---"
					echo "Mes: Abril"
					echo "Presione enter para continuar"
					read exit
				fi
			else
				logger -p local1.info "error, se ingreso una fecha invalida"
				echo "Error, fecha invalida"
				echo "presione enter para continuar"
				read exit
			fi
		fi
		;;
		5) 
		logger -p local1.info "buscando registros de mayo"
		month="May"
      		echo "Mes: $month"
		echo "Ingrese el dia: "
		read day
		if [ -z $day ]
		then
			logger -p local1.info "error, no se admiten campos vacios"
			echo "No se admiten fechas vacias"
			echo "Presione enter para continuar"
			read exit
		else
			if (( $day >= 1 && $day <= 31 ))
			then
				last -a | grep "$month $day"
				if [ $? == 0 ]
				then
					logger -p local1.info "mostrando registros del '$day' de Mayo"
					echo "presione enter para continuar"
					read exit
				else
					logger -p local1.info "no hay registros disponibles"
					echo "######################################"
					echo "--------No hay logs para mostrar------"
					echo "######################################"
					echo "Presione enter para continuar"
					read exit
				fi
			elif (( $day == 0 ))
			then
				last -a | grep "$month" | less
				if [ $? == 0 ]
				then
					logger -p local1.info "mostrando registros del mes de Mayo"
					echo "Presione enter para continuar"
					read exit
				else
					logger -p local1.info "no hay registros para mostrar"
					echo "#####################################"
					echo "---No hay registros para este mes---"
					echo "Mes: Mayo"
					echo "Presione enter para continuar"
					read exit
				fi
			else
				logger -p local1.info "error, la fecha ingresada no es valida"
				echo "Error, fecha invalida"
				echo "presione enter para continuar"
				read exit
			fi
		fi
		;;
		6) 
		logger -p local1.info "buscando registros de Junio"
		month="Jun"
      		echo "Mes: $month"
		echo "Ingrese el dia: "
		read day
		if [ -z $day ]
		then
			logger -p local1.info "error, no se admiten campos vacios"
			echo "No se admiten fechas vacias"
			echo "Presione enter para continuar"
			read exit
		else
			if (( $day >= 1 && $day <= 31 ))
			then
				last -a | grep "$month $day"
				if [ $? == 0 ]
				then
					logger -p local1.info "mostrando registros del '$day' de Junio"
					echo "presione enter para continuar"
					read exit
				else
					logger -p local1.info "no hay registros disponibles"
					echo "######################################"
					echo "--------No hay logs para mostrar------"
					echo "######################################"
					echo "Presione enter para continuar"
					read exit
				fi
			elif (( $day == 0 ))
			then
				last -a | grep "$month" | less
				if [ $? == 0 ]
				then
					logger -p local1.info "mostrando registros de Junio"
					echo "Presione enter para continuar"
					read exit
				else
					logger -p local1.info "no hay registros diponibles"
					echo "#####################################"
					echo "---No hay registros para este mes---"
					echo "Mes: Junio"
					echo "Presione enter para continuar"
					read exit
				fi
			else
				logger -p local1.info "error, la fecha ingresada no es valida"
				echo "Error, fecha invalida"
				echo "presione enter para continuar"
				read exit
			fi
		fi
		;;
		7) 
		logger -p local1.info "buscando registros de Julio"
		month="Jul"
      		echo "Mes: $month"
		echo "Ingrese el dia: "
		read day
		if [ -z $day ]
		then
			logger -p local1.info "error, no se admiten campos vacios"
			echo "No se admiten fechas vacias"
			echo "Presione enter para continuar"
			read exit
		else
			if (( $day >= 1 && $day <= 31 ))
			then
				last -a | grep "$month $day"
				if [ $? == 0 ]
				then
					logger -p local1.info "mostrando registros del '$day' de Julio"
					echo "presione enter para continuar"
					read exit
				else
					logger -p lical1.info "no hay registros disponibles"
					echo "######################################"
					echo "--------No hay logs para mostrar------"
					echo "######################################"
					echo "Presione enter para continuar"
					read exit
				fi
			elif (( $day == 0 ))
			then
				last -a | grep "$month" | less
				if [ $? == 0 ]
				then
					logger -p local1.info "mostrando registros de Julio"
					echo "Presione enter para continuar"
					read exit
				else
					logger -p local1.info "no hay registros disponibles"
					echo "#####################################"
					echo "---No hay registros para este mes---"
					echo "Mes: Julio"
					echo "Presione enter para continuar"
					read exit
				fi
			else
				logger -p local1.info "error, se ingreso una fecha invalida"
				echo "Error, fecha invalida"
				echo "presione enter para continuar"
				read exit
			fi
		fi
		;;
		8) 
		logger -p local1.info "buscando registros de Agosto"
		month="Aug"
      		echo "Mes: $month"
		echo "Ingrese el dia: "
		read day
		if [ -z $day ]
		then
			logger -p local1.info "error, no se admiten campos vacios"
			echo "No se admiten fechas vacias"
			echo "Presione enter para continuar"
			read exit
		else
			if (( $day >= 1 && $day <= 31 ))
			then
				last -a | grep "$month $day"
				if [ $? == 0 ]
				then
					logger -p local1.info "mostrando registros del '$day' de Agosto"
					echo "presione enter para continuar"
					read exit
				else
					logger -p local1.info "no hay registros disponibles"
					echo "######################################"
					echo "--------No hay logs para mostrar------"
					echo "######################################"
					echo "Presione enter para continuar"
					read exit
				fi
			elif (( $day == 0 ))
			then
				last -F | grep "$month" | less
				if [ $? == 0 ]
				then
					logger -p local1.info "mostrando registros de Agosto"
					echo "Presione enter para continuar"
					read exit
				else
					logger -p local1.info "no hay registros disponibles"
					echo "#####################################"
					echo "---No hay registros para este mes---"
					echo "Mes: Agosto"
					echo "Presione enter para continuar"
					read exit
				fi
			else
				logger -p local1.info "error, se ingreso una fecha invalida"
				echo "Error, fecha invalida"
				echo "presione enter para continuar"
				read exit
			fi
		fi
		;;
		9) 
		logger -p local1.info "buscando registros de Septiembre"
		month="Sep"
      		echo "Mes: $month"
		echo "Ingrese el dia: "
		read day
		if [ -z $day ]
		then
			logger -p local1.info "error, no se admiten campos vacios"
			echo "No se admiten fechas vacias"
			echo "Presione enter para continuar"
			read exit
		else
			if (( $day >= 1 && $day <= 31 ))
			then
				last -a | grep "$month $day"
				if [ $? == 0 ]
				then
					logger -p local1.info "mostrando registros del '$day' de Septiembre"
					echo "presione enter para continuar"
					read exit
				else
					logger -p local1.info "no hay registros disponibles"
					echo "######################################"
					echo "--------No hay logs para mostrar------"
					echo "######################################"
					echo "Presione enter para continuar"
					read exit
				fi
			elif (( $day == 0 ))
			then
				last -a | grep "$month" | less
				if [ $? == 0 ]
				then
					logger -p local1.info "mostrando registros de Septiembre"
					echo "Presione enter para continuar"
					read exit
				else
					logger -p local1.info "no hay registros disponibles"
					echo "#####################################"
					echo "---No hay registros para este mes---"
					echo "Mes: Septiembre"
					echo "Presione enter para continuar"
					read exit
				fi
			else
				logger -p local1.info "error, se ingreso una fecha invalida"
				echo "Error, fecha invalida"
				echo "presione enter para continuar"
				read exit
			fi
		fi
		;;
		10) 
		logger -p local1.info "buscando registros de Octubre"
		month="Oct"
      		echo "Mes: $month"
		echo "Ingrese el dia: "
		read day
		if [ -z $day ]
		then
			local1.info "error, no se admiten campos vacios"
			echo "No se admiten fechas vacias"
			echo "Presione enter para continuar"
			read exit
		else
			if (( $day >= 1 && $day <= 31 ))
			then
				last -a | grep "$month $day"
				if [ $? == 0 ]
				then
					logger -p local1.info "mostrando registros del '$day' de Octubre"
					echo "presione enter para continuar"
					read exit
				else
					logger -p local1.info "no existen registros disponibles"
					echo "######################################"
					echo "--------No hay logs para mostrar------"
					echo "######################################"
					echo "Presione enter para continuar"
					read exit
				fi
			elif (( $day == 0 ))
			then
				last -a | grep "$month" | less
				if [ $? == 0 ]
				then
					logger -p local1.info "mostrando registros de Octubre"
					echo "Presione enter para continuar"
					read exit
				else
					logger -p local1.info "no hay registros disponibles"
					echo "#####################################"
					echo "---No hay registros para este mes---"
					echo "Mes: Octubre"
					echo "Presione enter para continuar"
					read exit
				fi
			else
				logger -p local1.info "error, la fecha ingresada es invalida"
				echo "Error, fecha invalida"
				echo "presione enter para continuar"
				read exit
			fi
		fi
		;;
		11) 
		logger -p local1.info "buscando registros de Noviembre"
		month="Nov"
      		echo "Mes: $month"
		echo "Ingrese el dia: "
		read day
		if [ -z $day ]
		then
			logger -p local1.info "error, no se admiten campos vacios"
			echo "No se admiten fechas vacias"
			echo "Presione enter para continuar"
			read exit
		else
			if (( $day >= 1 && $day <= 31 ))
			then
				last -a | grep "$month $day"
				if [ $? == 0 ]
				then
					logger -p local1.info "mostrando registros del '$day' de Noviembre"
					echo "presione enter para continuar"
					read exit
				else
					logger -p local1.info "no hay registros para mostrar"
					echo "######################################"
					echo "--------No hay logs para mostrar------"
					echo "######################################"
					echo "Presione enter para continuar"
					read exit
				fi
			elif (( $day == 0 ))
			then
				last -a | grep "$month" | less
				if [ $? == 0 ]
				then
					logger -p local1.info "mostrando registros de Noviembre"
					echo "Presione enter para continuar"
					read exit
				else
					logger -p local1.info "no hay registros disponibles"
					echo "#####################################"
					echo "---No hay registros para este mes---"
					echo "Mes: Noviembre"
					echo "Presione enter para continuar"
					read exit
				fi
			else
				logger -p local1.info "error, la fecha ingresada es invalida"
				echo "Error, fecha invalida"
				echo "presione enter para continuar"
				read exit
			fi
		fi
		;;
		12) 
		logger -p local1.info "buscando registros de diciembre"
		month="Dec"
      		echo "Mes: $month"
		echo "Ingrese el dia: "
		read day
		if [ -z $day ]
		then
			logger -p local1.info "error, no se admiten campos vacios"
			echo "No se admiten fechas vacias"
			echo "Presione enter para continuar"
			read exit
		else
			if (( $day >= 1 && $day <= 31 ))
			then
				last -a | grep "$month $day"
				if [ $? == 0 ]
				then
					logger -p local1.info "mostrando registros del '$day' de Diciembre"
					echo "presione enter para continuar"
					read exit
				else
					logger -p local1.info "no hay registros para mostrar"
					echo "######################################"
					echo "--------No hay logs para mostrar------"
					echo "######################################"
					echo "Presione enter para continuar"
					read exit
				fi
			elif (( $day == 0 ))
			then
				last -a | grep "$month" | less
				if [ $? == 0 ]
				then
					logger -p local1.info "mostrando registros de Diciembre"
					echo "Presione enter para continuar"
					read exit
				else
					logger -p local1.info "no hay registros disponibles"
					echo "#####################################"
					echo "---No hay registros para este mes---"
					echo "Mes: Diciembre"
					echo "Presione enter para continuar"
					read exit
				fi
			else
				logger -p local1.info "error, se ingreso una fecha invalida"
				echo "Error, fecha invalida"
				echo "presione enter para continuar"
				read exit
			fi
		fi
		;;
		*)
		logger -p local1.info "error, se ingreso una opcion invalida"
		echo "Error, ingrese una opcion valida"
		echo "Presione enter para continuar"
		read exit
		;;
	esac
	else
		logger -p local1.info "error, se quiso ingresar un valor que no es numerico"
		echo "error se ingreso un valor que no es numerico"
		echo "presione enter para continuar"
		read exit
	fi
	;;
	6)
	logger -p local1.info "opcion: ver registros de cambios en el sistema"
	clear
	last -x | less
	if [ $? == 0 ]
	then
		logger -p local1.info "mostrando registros de los cambios en el sistema"
		echo "presione enter para continuar"
		read exit
	else
		logger -p local1.info "ocurrio un error al solicitar los registros"
		echo "error"
		echo "presione enter para continuar"
		read exit
	fi
	;;
	7)
	logger -p local1.info "mostrando registros de los ultimos ingresos al sistema"
	clear
	lastlog
	echo "presione enter para continuar"
	read exit
	;;
	8)
	logger -p local1.info "opcion: mostrar accesos fallidos al servidor de respaldo, intentando conectar"
	echo "Conectando con el servidor de respaldo..."
	timeout 30s ssh -p2244 respaldo@backupServer "lastb -a | less"
	if [ $? == 0 ]
	then
		logger -p local1.info "mostrando los registros de acceso fallido al servidor de respaldo"
		echo "Presione enter para continuar"
		read exit
	else
		logger -p local1.info "error, no se pudo establecer conexion con el servidor"
		echo "No se pudo establecer una conexion con el servidor"
		echo "Presione enter para continuar"
		read exit
	fi
	;;
	9)
	logger -p local1.info "opcion: mostrar los logs de los menus de sistema"
	echo "Logs de menus"
	cat /var/log/customlogs/menu_backup.log | less
	echo "presione enter para continuar"
	read exit
	;;
	0)
	logger -p local1.info "saliendo del menu de auditoria"
	clear
	;;
	*)
	logger -p local1.info "error, se ingreso una opcion invalida"
	echo "Opcion incorrecta"
	sleep 2s
	;;
	esac
	else
	logger -p local1.info "error el valor ingresado debe ser numerico"
	echo "error, el valor debe ser numerico"
	echo "presione enter para continuar"
	read exit
	fi
done
