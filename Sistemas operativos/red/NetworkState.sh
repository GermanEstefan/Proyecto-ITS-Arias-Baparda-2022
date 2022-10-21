#!bin/bash
logger -p local1.info "Inicia el script de diagnostico del servidor"
op=1
while [ $op != 0 ]
do
	clear
	echo "-----------------------------------"
	echo "Menu principal"
	echo "-----------------------------------"
	echo "1) Comprobar si el servidor esta operativo"
	echo "2) Comprobar si el servicio ssh esta corriendo"
	echo "3) Comprobar el estado de los respaldos"
	echo "0) Salir"
	echo "Ingrese una opcion"
	read op
	case $op in
	1)
	logger -p local1.info "Comprobando si el servidor esta operativo"
	clear
	echo "Comprobando, esto puede tardar unos minutos...."
	echo "-----------------------------------------------"
	logger -p local1.info "Intentando hacer ping al servidor"
	ping -c3 192.168.1.2 | grep "Destination Host Unreachable"
	if [ $? != 0 ]
	then
		logger -p local1.info "ping realizado con exito, intentando escanear puertos"
		nmap -Pn 192.168.1.2
		if [ $? == 0 ]
		then
			logger -p local1.info "puertos escaneados con exito"
			echo "------------------------------------------"
			echo "__________________________________________"
			echo "El servidor esta UP"
			echo "Presiona enter para continuar"
			read exit
		else
			logger -p local1.info "todos los puertos filtrados, no se encontraron puertos disponibles"
			echo "------------------------------------------"
			echo "__________________________________________"
			echo "El servidor tiene "
			read exit
		fi
	else
		logger -p local1.info "no se pudo realizar el ping al servidor, servidor caido"
		echo "------------------------------------------"
		echo "__________________________________________"
		echo "No se pudo establecer una conexion con el servidor"
		echo "Presiona enter para continuar"
		read exit
	fi
	;;
	2)
	clear
	logger -p local1.info "intentando comprobar si el servicio ssh esta disponible"
	echo "Comprobando..."
	nmap 192.168.1.2 -p 2244 -Pn | grep open
	if [ $? == 0 ]
	then
		logger -p local1.info "...servicio ssh disponible"
		clear
		echo "-----------------------------------------"
		echo "_________________________________________"
		echo "El servicio ssh se encuentra escuchando"
		echo "Presiona enter para continuar"
		read exit
	else
		logger -p local1.info "...servicio ssh NO disponible"
		clear
		echo "-----------------------------------------"
		echo "_________________________________________"
		echo "No se pudo establecer la conexion"
		echo "Bien porque el servicio se encuentra caido, o el servidor esta caido"
		echo "Presiona enter para continuar"
		read exit
	fi
	;;
	3)
	logger -p local1.info "Intentando comprobar acceso a la carpeta de respaldos de la DB"
	clear
	echo "Comprobando..."
	echo "Puede tardar unos minutos...."
	rsync -ahzP --dry-run -e "ssh -p 2244" respaldo@192.168.1.2:/home/respaldo/respaldos /home/master/respaldos | grep respaldo.tar.gz
	if [ $? == 0 ]
	then
		logger -p local1.info "...conexion con la carpeta de respaldos establecida"
		clear
		echo "-----------------------------------------------"
		echo "_______________________________________________"
		echo "Conexion establecida con exito"
		echo "Presiona enter para continuar"
		read exit
	else
		logger -p local1.info "...no se pudo establecer la conexion a la carpeta de respaldos"
		clear
		echo "-----------------------------------------------"
		echo "_______________________________________________"
		echo "No se puede establecer conexion con la carpeta de respaldos"
		echo "Presiona enter para continuar"
		read exit
	fi
	;;
	0)
	logger -p local1.info "Saliendo del script de diagnostico del servidor"
	echo "bye"
	clear
	;;
	*)
	echo "Ingrese una opcion correcta"
	sleep 2
	;;
	esac
done
