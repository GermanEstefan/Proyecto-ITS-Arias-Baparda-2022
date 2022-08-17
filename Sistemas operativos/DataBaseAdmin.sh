#!/bin/bash
#
#variables
DATABASE="tareaSO"
USER="admin"
PASSWORD="1234"
RESPALDOS="/home/fabricio/respaldos"
DAILY="@daily"
WEEKLY="@weekly"
CUSTOMCRON=""
#
#
#Menu principal
opt=1
opt2=1
while [ "$opt" != 0 ]
	do
	echo "Bienvenido al sistema de administracion de la base de datos"
	echo "Para continuar elija una opcion"
	echo "--------------------------------"
	echo "1) Crear un respaldo"
	echo "2) Importar respaldo"
	echo "3) Configuración de los respaldos"
	echo "4) Administrar usuarios de la base de datos"
	echo "0) Salir"
	read opt
	case $opt in
		1)
		clear
		echo "Base de datos: "$DATABASE
		echo "--------------------------------------------------"
		mysqldump -u $USER -p$PASSWORD $DATABASE >> $RESPALDOS/$DATABASE".sql"
		tar -zcvf $RESPALDOS/$(date +%d-%m-%y)_respaldo.tar.gz $RESPALDOS/$DATABASE".sql"
		rm $RESPALDOS/$DATABASE".sql"
		echo "Backup realizado en /home/fabricio/scripts/"
		ls $RESPALDOS/
		sleep 2s
		;;
		2)
		clear
		echo "Registro de respaldos"
		ls -1hr $RESPALDOS/
		echo "Seleccione un respaldo de la lista"
		read resp
		if [ -d $RESPALDOS/$resp ]
		then
			tar -zxf $RESPALDO/respaldo.tar.gz
			mysql -u $USER -p$PASSWORD $DATABASE < $DATABASE".sql"
			rm tareaSO.sql
			echo "Importación realizada"
			sleep 1s
		else
			echo "El archivo que quieres importar no existe"
			echo "asegurate de que el nombre este bien escrito"
			echo "--------------------------------------------"
			sleep 1s
		fi
		;;
		3)
		clear
		while [ "$opt2" != 0 ]
		do 
		echo "Configuración por defecto:"
		echo "1) Ruta de los respaldos: "$RESPALDOS/
		echo "2) Asiduidad de los respaldos: "$DAILY
		echo "3) Ruta del servidor de respaldo: "$BACKUPSERVER
		echo "0) Volver al menu principal"
		echo "---------------------------------------------------"
		echo "SELECCIONE LA CONFIGURACION QUE DESEA CAMBIAR"
		read opt2
		case $opt2 in
			1)
			echo "Escriba el nombre de la ruta donde esta la carpeta de respaldos"
			read ruta
			if [ -d $ruta ]
			then
				sed -i s%$RESPALDOS%$ruta%g /home/fabricio/scripts/DataBaseAdminPrueba.sh
				echo "La nueva ruta es: "$ruta
				sleep 2s
			else
				echo "La ruta que selecciono no es valida"
				sleep 1s
			fi
			;;
			2)
			;;
			3)
			;;
			0)
			echo "Menu Principal"
			;;
			*)
			echo "Seleccione una opcion valida"
			sleep 1s
			;;
		esac
		done
		;;
		4)
		clear
		echo "caso 4"
		;;
		0)
		echo "Bye"
		;;
		*)
		clear
		echo "Opcion invalida"
		;;
		esac
	done