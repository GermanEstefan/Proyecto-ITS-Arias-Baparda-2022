#!/bin/bash

#archivos de configuracion
cat /etc/my.cnf > /var/log/customlogs/mariadb.config.log
cat /etc/httpd/conf/httpd.conf > /var/log/customlogs/apache.config.log 
cat /etc/rsyslog.conf > /var/log/customlogs/rsyslog.cong.log 
cat /etc/php.ini > /var/log/customlogs/php.conf.log
cat /etc/rsyncd.conf > /var/log/customlogs/rsync.conf.log

#paquetes instalados
rpm -qa > /var/log/customlogs/paquetesInstalados.log

#actualizaciones
sudo -pbindev yum history > /var/log/customlogs/yumHistorial.log

#usuarios y grupos
cat /etc/passwd > /var/log/customlogs/usuarios.log
cat /etc/group > /var/log/customlogs/grupos.log
