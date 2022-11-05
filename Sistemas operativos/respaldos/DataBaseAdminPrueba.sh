#!/bin/bash

#variables
DATABASE="bindev"
USER="master"
PASSWORD="1234"
RESPALDOS="/home/master/respaldos"
SERVIDOR="respaldo@backupServer:/home/respaldo/respaldos"
logger -p local1.info "Ingreso al menu de respaldos de la Base de datos"


#Menu principal
opt=1
while [ "$opt" != 0 ]
	do
	echo "Bienvenido al sistema de respaldos de la base de datos"
	echo "Para continuar elija una opcion"
	echo "--------------------------------"
	echo "1) Crear un respaldo de la Base de datos"
	echo "2) Importar respaldo a la Base de datos"
	echo "3) ver el estado del servicio mariaDB"
	echo "4) Probar conexion al servidor de respaldos"
	echo "0) Salir"
	read opt
	case $opt in
		1)
		clear
		logger -p local1.info "Comenzo intendo de realizar un respaldo de la BD"
		echo "Base de datos: "$DATABASE
		echo "--------------------------------------------------"
		mysqldump -u $USER -p$PASSWORD $DATABASE > $RESPALDOS/$DATABASE".sql"
		if [ $? -eq 0 ]
		then
			logger -p local1.info "Se ha exportado la DB con exito"
			tar -zcvf ./"$(date +'%d-%m-%y')_hora_$(date +'%R')_respaldo_manual.tar.gz" $RESPALDOS/$DATABASE".sql" 
			if [ $? -eq 0 ]
			then
				logger -p local1.info "Se ha llevado a cabo la compresion del archivo"
				mv $(find . -name "*.tar.gz") $RESPALDOS
				rm $RESPALDOS/$DATABASE".sql"
				rsync --remove-source-files -avhzP -e "ssh -p 2244" $RESPALDOS/ $SERVIDOR
				if [ $? -eq 0 ]
				then
					echo "Respaldo completado..."
					logger -p local1.info "Respaldo de la base de datos creado"
					sleep 2s
				else
					echo "#############  Algo salio MUY mal  ##############"
					echo "No se completo el respaldo"
					logger -p local1.info "No se pudo completar el respaldo. Sucedio un fallo en la conexion hacia el servidor"
					sleep 2s
				fi
			else
				echo "Fallo en la compresion"
				logger -p local1.info "Fallo en la compresion"
			fi
		else
		echo "Error al exportar desde la base de datos"
		logger -p local1.info "No se pudo hacer el respaldo, sucedio un error en la BD"
		fi
		;;
		2)
		clear
		echo "Importando ultimo registro disponible..."
		resp=$(rsync -avhz --dry-run -e "ssh -p 2244" $SERVIDOR $RESPALDOS | grep respaldo.tar.gz | tail -1 | cut -f2 -d"/")
		echo "Ultimo registro disponible -> "$resp
		sleep 1s
		rsync -avhzP --include=${resp} --exclude '*' -e "ssh -p 2244" $SERVIDOR/ $RESPALDOS
		if [ $? -eq 0 ]
		then
			echo "Respaldo importado desde el servidor.."
			logger -p local1.info "Respaldo $resp importado desde el servidor"
			sleep 1s
			tar -zxf $RESPALDOS/$resp -C $RESPALDOS/
			if [ $? -eq 0 ]
			then
				echo "Descomprimiendo archivos..."
				logger -p local1.info "Descomprimiendo archivos para importar a la base de datos, $resp"
				ls $RESPALDOS
				echo "Importando a la base de datos.."
				mysql -u $USER -p$PASSWORD $DATABASE < $RESPALDOS/home/master/respaldos/$DATABASE".sql"
				if [ $? -eq 0 ]
				then
					rm -r $RESPALDOS/*
					echo "Exito!"
					logger -p local1.info "Respaldo importado desde el servidor"
					echo "################################"
				else
					"Ocurrio un error al importar el respaldo a la BD"
					logger -p local1.info "Error al importar hacia la BD"
				fi
			else
				echo "No se pudo descomprimir el archivo"
				logger -p local1.info "No se pudo descomprimir el archivo"
			fi
		else
			echo "Algo salio mal al importar desde el servidor"
			logger -p local1.info "Ocurrio un error al importar el respaldo desde el servidor externo"
		fi
		;;
		3)
		systemctl status mariadb | grep active
		logger -p local1.info "Info del estado del servicio mariadb"
		sleep 2s
		clear
		;;
		4)
		clear
		echo "Sincronizando con el servidor de respaldo"
		echo "-----------------------------------------"
		timeout 1m rsync -ahzP --dry-run -e "ssh -p 2244" $SERVIDOR $RESPALDOS | grep respaldo.tar.gz
		if [ $? -eq 0 ]
		then
			sleep 1s
			echo "#################################################"
			echo "Conexion establecida con exito, Presione enter para volver al menu principal"
			logger -p local1.info "Probando conexion al servidor de respaldo, EXITO"
			read cosa
			clear
		else
			sleep 1s
			echo "#################################################"
			echo "El servidor esta OFFLINE o no se puede acceder al mismo, Presione enter para volver al menu principal"
			logger -p local1.info "Probando conexion al servidor de respaldos, No se puede conectar con el servidor"
			read cosa
			clear
		fi
		;;
		0)
		logger -p local1.info "Salida del menu de respaldos de la base de datos"
		clear
		;;
		*)
		clear
		echo "Opcion invalida"
		;;
		esac
	done

