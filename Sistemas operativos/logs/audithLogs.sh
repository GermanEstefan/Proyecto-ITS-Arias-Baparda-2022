#!bin/bash

logger -p local1.info "Ejecutando el script de logs de auditoria"
sudo last > /var/log/customlogs/last.log
if [ $? == 0 ]
then
	logger -p local1.info "exito al ejecutar los last del sistema"
else
	logger -p local1.info "fallo al intentar exportar los logs de last"
fi
sudo echo "$(lastlog | grep -v "Nunca ha accedido")" > /var/log/customlogs/last_log.log
if [ $? == 0 ]
then
	logger -p local1.info "exito al ejecutar los lastlog del sistema"
else
	logger -p local1.info "fallo al intentar exportar los logs de lastlog"
fi
logger -p local1.info "finalizo el script de logs de auditoria"
