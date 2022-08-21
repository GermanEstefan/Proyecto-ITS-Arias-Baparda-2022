#!/bin/bash

#variables
DATABASE="tareaSO"
USER="admin"
PASSWORD="1234"
RESPALDOS="/home/fabricio/respaldos"
SERVIDOR="respaldo@192.168.1.2:/home/respaldo"
DAILY="@daily"
WEEKLY="@weekly"
CUSTOMCRON=""
echo "Ejecutando el script de respaldo de la base de datos"
echo "--------------------------------------------------"
mysqldump -u $USER -p$PASSWORD $DATABASE >> $RESPALDOS/$DATABASE".sql"
tar -zcvf $RESPALDOS/$(date +%d-%m-%y)_respaldo.tar.gz $RESPALDOS/$DATABASE".sql"
rm $RESPALDOS/$DATABASE".sql"
rsync -ahvzP -e "ssh -p 2244" $RESPALDOS/ $SERVIDOR
echo "Backup realizado en /home/fabricio/respaldos/"
echo "Sincronización con el servidor de respaldo completada"
ls $RESPALDOS/
