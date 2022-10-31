#!/bin/bash

#variables
DATABASE="bindev"
USER="master"
PASSWORD="1234"
RESPALDOS="/home/master/respaldos"
SERVIDOR="respaldo@backupServer:/home/respaldo/respaldos"

echo "Ejecutando el script de respaldo de la base de datos"
logger -p local1.info "Backup de la BD automatico iniciado"
echo "--------------------------------------------------"
mysqldump -u $USER -p$PASSWORD $DATABASE >> $RESPALDOS/$DATABASE".sql"
if [ $? -eq 0 ]
then
	logger -p local1.info "dump realizado"
	tar -zcvf $RESPALDOS/$(date +%d-%m-%y-%R)_respaldo.tar.gz $RESPALDOS/$DATABASE".sql"
	if [ $? -eq 0 ]
	then
		rm $RESPALDOS/$DATABASE".sql"
		rsync --remove-source-files -ahvzP -e "ssh -p 2244" $RESPALDOS/ $SERVIDOR
		if [ $? -eq 0 ]
		then
			echo "Backup realizado"
			logger -p local1.info "Backup realizado con exito"
		else
			echo "No se pudo sincronizar con el servidor de respaldo"
			logger -p local1.info "Ocurrio un error al querer exportart hacia el servidor de respaldo"
		fi
	else
	echo "ocurrio un error"
	logger -p local1.info "No se pudo comprimir el archivo dump"
	fi
else
	echo "ocurrio un error"
	logger -p local1.info "No se pudo realizar el dump de la BD"
	sleep 2s
fi
