#!bin/bash
#Menu de administracion de servicios
logger -p local1.info "Inicio Menu de administracion de servicios"

op=1
while [ "$op" != 0 ]
do
	clear
	echo "Menu de administracion de servicios"
	echo "-----------------------------------"
	echo "1) Servidor Apache: "
	echo "Estado:..." 
	systemctl status httpd | grep 'Active' | cut -f2 -d':'
	echo "-----------------------------------"
	echo "2) Servicio MySQL"
	echo "Estado:..."
	systemctl status mariadb | grep 'Active' | cut -f2 -d':'
	echo "-----------------------------------"
	echo "3) Servicio Prometheus"
	echo "Estado:..."
	systemctl status prometheus.service | grep 'Active' | cut -f2 -d':'
	echo "-----------------------------------"
	echo "4) Servicio SSH"
	echo "Estado:..."
	systemctl status sshd | grep 'Active' | cut -f2 -d':'
	echo "-----------------------------------"
	echo "5) Antivirus"
	echo "Estado:..."
	systemctl status clamd@scan | grep 'Active' | cut -f2 -d':'
	echo "-----------------------------------"
	echo "6) Grafana Server"
	echo "Estado:..."
	systemctl status grafana-server | grep 'Active' | cut -f2 -d':'
	echo "----------------------------------"
	echo "0) Salir"
	echo "----------------------------------"
	echo "----------------------------------"
	echo "Elija una opcion"
	read op
	case $op in
		1)
		logger -p local1.info "administrando servicio Apache"
		op1=1
		while [ $op1 != 0 ]
		do
		clear
		echo "Servidor Apache"
		echo "Estado.."
		systemctl status httpd
		echo "---------------------------"
		echo "---------------------------"
		echo "1) Iniciar servicio"
		echo "2) Detener servicio"
		echo "3) Resetear servicio"
		echo "0) Volver al menu principal"
		echo "------------------------------------"
		echo "Seleccione una opcion"
		read op1
			case $op1 in
			1)
			logger -p local1.info "Intentando iniciar el servicio"
			sudo systemctl start httpd
			if [ $? == 0 ]
			then
				logger -p local1.info "...servicio iniciado"
			else
				logger -p local1.info "...no se pudo iniciar el servicio"
			fi
			;;
			2)
			logger -p local1.info "Intentando detener el servicio"
			sudo systemctl stop httpd
			if [ $? == 0 ]
			then
				logger -p local1.info "...servicio detenido"
			else
				logger -p local1.info "...no se pudo detener el servicio"
			fi
			;;
			3)
			logger -p local1.info "Intentando resetear el servicio"
			sudo systemctl restart httpd
			if [ $? == 0 ]
			then
				logger -p local1.info "...servicio reseteado"
			else
				logger -p local1.info "...no se pudo retear el servicio"
			fi
			;;
			*)
			echo "Opcion incorrecta"
			;;
			esac
		done
		;;
		2)
		logger -p local1.info "Administrando servicio MySQL"
		op2=1
		while [ $op2 != 0 ]
		do
		clear
		echo "Servicio MySQL"
		echo "Estado..."
		systemctl status mariadb
		echo "-----------------------------"
		echo "-----------------------------"
		echo "1) Iniciar servicio"
		echo "2) Detener servicio"
		echo "3) Resetear servicio"
		echo "0) Volver al menu principal"
		read op2
			case $op2 in
			1)
			logger -p local1.info "Intentando iniciar el servicio"
			sudo systemctl start mariadb
			if [ $? == 0 ]
			then
				logger -p local1.info "...servicio iniciado"
			else
				logger -p local1.info "...no se pudo iniciar el servicio"
			fi
			;;
			2)
			logger -p local1.info "Intentando detener el servicio"
			sudo systemctl stop mariadb
			if [ $? == 0 ]
			then
				logger -p local1.info "...servicio detenido"
			else
				logger -p local1.info "...no se puede detener el servicio"
			fi
			;;
			3)
			logger -p local1.info "Intentando resetear el servicio"
			sudo systemctl restart mariadb
			if [ $? == 0 ]
			then
				logger -p local1.info "...servicio reseteado"
			else
				logger -p local1.info "...no se puede resetear el servicio"
			fi
			;;
			*)
			echo "Opcion incorrecta"
			;;
			esac
		done
		;;
		3)
		logger -p local1.info "administrando el servicio de prometheus"
		op3=1
		while [ $op3 != 0 ]
		do
		clear
		echo "Servicio de monitorieo"
		echo "Estado.."
		systemctl status prometheus.service
		echo "---------------------------------------"
		echo "---------------------------------------"
		echo "1) Inciar servicio"
		echo "2) Detener servicio"
		echo "3) Resetear servicio"
		echo "0) Volver al menu principal"
		read op3
			case $op3 in
			1)
			logger -p local1.info "Intentando iniciar el servicio"
			sudo systemctl start prometheus.service
			if [ $? == 0 ]
			then
				logger -p local1.info "...servicio iniciado"
			else
				logger -p local1.info "...no se puede iniciar el servicio"
			fi
			;;
			2)
			logger -p local1.info "Intentando detener el servicio"
			sudo systemctl stop prometheus.service
			if [ $? == 0 ]
			then
				logger -p local1.info "...servicio detenido"
			else
				logger -p local1.info "...no se puede detener el servicio"
			fi
			;;
			3)
			logger -p local1.info "Intentando retear el servicio"
			sudo systemctl restart prometheus.service
			if [ $? == 0 ]
			then
				logger -p local1.info "...servicio reseteado"
			else
				logger -p local1.info "...no se puede reseteado el servicio"
			fi
			;;
			*)
			echo "Opcion incorrecta"
			;;
			esac
		done
		;;
		4)
		logger -p local1.info "administrando el servicio SSH"
		op4=1
		while [ $op4 != 0 ]
		do
		clear
		echo "Servicio SSH"
		echo "Estado..."
		systemctl status sshd
		echo "--------------------------------------"
		echo "--------------------------------------"
		echo "1) Iniciar servicio"
		echo "2) Detener servicio"
		echo "3) Resetear servicio"
		echo "0) Volver al menu principal"
		echo "Seleccione una opcion"
		read op4
			case $op4 in
			1)
			logger -p local1.info "Intentando iniciar el servicio"
			sudo systemctl start sshd
			if [ $? == 0 ]
			then
				logger -p local1.info "...servicio iniciado"
			else
				logger -p local1.info "...no se puede iniciar el servicio"
			fi
			;;
			2)
			logger -p local1.info "Intentando detener el servicio"
			sudo systemctl stop sshd
			if [ $? == 0 ]
			then
				logger -p local1.info "...servicio detenido"
			else
				logger -p local1.info "...no se puede detener el servicio"
			fi
			;;
			3)
			logger -p local1.info "intentando resetear el servicio"
			sudo systemctl restart sshd
			if [ $? == 0 ]
			then
				logger -p local1.info "...servicio reseteado"
			else
				logger -p local1.info "...no se puede resetear el servicio"
			fi
			;;
			*)
			echo "Opcion incorrecta"
			;;
			esac
		done
		;;
		5)
		logger -p local1.info "administrando el servicio de Clam-av"
		op5=1
		while [ $op5 != 0 ]
		do
		clear
		echo "Antivirus"
		echo "Estado"
		systemctl status clamd@scan
		echo "------------------------------------"
		echo "------------------------------------"
		echo "1) Iniciar servicio"
		echo "2) Detener servicio"
		echo "3) Resetear servicio"
		echo "0) Volver al menu principal"
		echo "Ingrese una opcion"
		read op5
			case $op5 in
			1)
			logger -p local1.info "Intendando iniciar el servicio"
			sudo systemctl start clamd@scan
			if [ $? == 0 ]
			then
				logger -p local1.info "...servicio iniciado"
			else
				logger -p local1.info "...no se puede iniciar el servicio"
			fi
			;;
			2)
			logger -p local1.info "Intendando detener el servicio"
			sudo systemctl stop clamd@scan
			if [ $? == 0 ]
			then
				logger -p local1.info "...servicio detenido"
			else
				logger -p local1.info "...no se pudo detener el servicio"
			fi
			;;
			3)
			logger -p local1.info "Intendando resetear el servicio"
			sudo systemctl restart clamd@scan
			if [ $? == 0 ]
			then
				logger -p local1.info "...servicio reseteado"
			else
				logger -p local1.info "...no se puede resetear el servicio"
			fi
			;;
			*)
			echo "Ingrese una opcion correcta"
			;;
			esac
		done
		;; 
		6)
		logger -p local1.info "administrando el servicio de Grafana-server"
		op6=1
		while [ $op6 != 0 ]
		do
		clear
		echo "Granafa Server"
		echo "Estado"
		systemctl status grafana-server
		echo "------------------------------------"
		echo "------------------------------------"
		echo "1) Activar servicio"
		echo "2) Detener servicio"
		echo "3) Resetear servicio"
		echo "0) Volver al menu principal"
		echo "Ingrese una opcion"
		read op6
		case $op6 in
			1)
			logger -p local1.info "intentando iniciar el servicio"
			sudo systemctl start grafana-server
			if [ $? == 0 ]
			then
				logger -p local1.info "...servicio iniciado"
			else
				logger -p local1.info "...no se puede iniciar el servicio"
			fi
			;;
			2)
			logger -p local1.info "Intentando detener el servicio"
			sudo systemctl stop grafana-server
			if [ $? == 0 ]
			then
				logger -p local1.info "...servicio detenido"
			else
				logger -p local1.info "...no se puede detener el servicio"
			fi
			;;
			3)
			logger -p local1.info "Intentando resetear el servicio"
			sudo systemctl restart grafana-server
			if [ $? == 0 ]
			then
				logger -p local1.info "...servicio reseteado"
			else
				logger -p local1.info "...no se puede resetear el servicio"
			fi
			;;
			*)
			echo "Opcion incorrecta"
			;;
		esac
		done
		;;
		0)
		logger -p local1.info "Saliendo del script de administracion de servicios"
		clear
		;;
		*)
		clear
		echo "Opcion incorrecta"
		;;
	esac
done
