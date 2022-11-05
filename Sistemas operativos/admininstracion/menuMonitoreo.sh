#!/bin/bash
#Menu de monitoreo de recursos
logger -p local1.info "entrando en el menu de monitoreo de sistema"
op=1
while [ $op != 0 ]
do
	clear
	echo "Menu de monitoreo"
	echo "---------------------------------------------"
	echo "1) ver uso de los recursos del sistema"
	echo "2) ver espacio disponible en el disco duro"
	echo "3) ver memoria RAM disponible"
	echo "0) salir"
	read op
	case $op in 
	1)
	logger -p local1.info "mostrando monitoreo de sistema"
	htop
	;;
	2)
	logger -p local1.info "mostrando espacio de disco duro"
	df -h
	echo "Presione enter para continuar"
	read exit
	;;
	3)
	logger -p local1.info "mostrando información de memoria RAM"
	vmstat -s -S M
	echo "Presione enter para continuar"
	read exit
	;;
	0)
	logger -p local1.info "saliendo del menu de monitoreo"
	clear
	;;
	*)
	logger -p local1.info "se ingreso una opcion incorrecta"
	echo "opcion incorrecta"
	echo "Presione enter para continuar"
	read exit
	;;
	esac
done
