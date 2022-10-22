#!bin/bash
clear
logger -p local1.info "Iniciado el script de gestion de scripts"
op=1
while [ $op != 0 ]
do
	echo clear
	echo "Bienvenido al sistema de gestion de scripts"
	echo "___________________________________________"
	echo "Scripts disponibles"
	echo "1) Administracion de usuarios y grupos"
	echo "2) Exportacion e importacion de respaldos de la base de datos"
	echo "3) Administracion de servicios"
	echo "4) Menu de administracion de la base de datos"
	echo "5) Comprobar el estado del servidor de respaldo"
	echo "0) Salir"
	echo "-------------------------------------------"
	echo "Seleccione una opcion..."
	read op
	case $op in
	1)
	logger -p local1.info "Intento de iniciar el script de administracion de Usu y Grup"
	clear
	echo "Administracion de usuarios y grupos"
	sh /home/master/Scripts/admininstracion/usersManagement.sh
	;;
	2)
	logger -p local1.info "Intento de iniciar el script de respaldos de la DB"
	clear
	sh /home/master/Scripts/respaldos/DataBaseAdminPrueba.sh
	;;
	3)
	logger -p local1.info "Intento de iniciar el script de administracion de servicios"
	clear
	sh /home/master/Scripts/admininstracion/menuAdminServicios.sh
	;;
	4)
	logger -p local1.info "Intento de iniciar el script de la administracion de la DB"
	clear
	sh /home/master/Scripts/admininstracion/adminDB.sh
	;;
	5)
	logger -p local1.info "Intento de iniciar el script de diagnostico del servidor"
	clear
	sh /home/master/Scripts/red/NetworkState.sh
	;;
	0)
	echo "bye"
	;;
	*)
	clear
	echo "Opcion incorrecta"
	sleep 1s
	;;
	esac
done