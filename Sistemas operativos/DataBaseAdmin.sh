#!/bin/bash

#Menu principal
opt=1
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
		if [ -n "$DATABASE" ]
		then
			echo "Actualmente hay una base de datos seleccionada"
			echo "BD: "$DATABASE
			echo "--------------------------------------------------"
			echo "Desea continuar con esta base de datos Y/n"
			read answer
			if [ $answer = "Y" ] | [ $answer = "y" ]
			then
				mysqldump -u admin -p1234 $DATABASE >> /home/fabricio/respaldos/$DATABASE".sql"
				tar -zcvf /home/fabricio/respaldos/$(date +%d-%m-%y)_respaldo.tar.gz /home/fabricio/respaldos/$DATABASE".sql"
				rm /home/fabricio/respaldos/$DATABASE".sql"
				echo "Backup realizado en /home/fabricio/respaldos/"
				ls /home/fabricio/respaldos/
				sleep 2s
			else
				echo "--------------------------------------"
				echo "Escriba el nombre de la base de datos:"
				read db
				export DATABASE=$db
				if [ -e /home/fabricio/respaldos/database.txt ]
				then
					$DATABASE":" >> /home/fabricio/respaldos/database.txt
				else
					$DATABASE":" > /home/fabricio/respaldos/database.txt
				fi
			fi
		else
			echo "No hay base de datos seleccionada"
			echo "---------------------------------------"
			echo "Bases de datos disponibles"
			if [ -e /home/fabricio/respaldos/database.txt ]
			then
				cat /home/fabricio/respaldos/database.txt | cut -d":" -f 1
			else
				echo "No hay bases de datos guardadas"
			fi
			echo "Escriba el nombre de la base de datos a utilizar:"
			read db
			export DATABASE=$db
			echo $DATABASE":" > /home/fabricio/respaldos/database.txt
		fi
		;;
		2)
		clear
		tar -zxf respaldo.tar.gz
		mysql -u admin -p1234 tareaSO < tareaSO".sql"
		rm tareaSO.sql
		echo "Importación realizada"
		;;
		3)
		clear 
		echo "Caso 3"
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

