#!/bin/bash
opt=0
while [ "opt" != "9" ]
	do
	echo "Selecciona una opcion"
	echo "1- Agregar un usuario"
	echo "2- Eliminar un usuario"
	echo "3- Buscar usuario por nombre"
	echo "4- Agregar un nuevo grupo"
	echo "5- Eliminar un grupo"
	echo "6- Agregar usuario a grupo"
	echo "7- Eliminar usuario de un grupo"
	echo "8- Listar usuarios"
	echo "9- Listar grupos"
	echo "10- Salir"
	read opt
	case $opt in
		1)
		read -p "Nombre de usuario: " userName
		if [ $(cat /etc/passwd | cut -f1 -d':' | grep -c $userName) != 0 ]
		then
			echo "El usuario $userName ya existe"
			continue
		else
			sudo useradd $userName
			echo "Usuario $userName creado"
		fi
		read -p "¿Desea asignarle una contraseña? y | n " asignPasswd
		if [ $asignPasswd = "y" ]
		then
			sudo passwd $userName
			echo "Contraseña creada"
		elif [ $asignPasswd = "n" ]
		then
			echo "Usuario sin contraseña"
		else
			echo "Opcion incorrecta"
			continue
		fi
		clear
		;;
		2)
		read -p "Nombre de usuario: " userName
		if [ $(cat /etc/passwd | cut -f1 -d':' | grep -c $userName) != 0 ]
		then
			sudo deluser $userName
		else
			echo "El usuario $userName no existe"
		fi
		;;
		3)
		read -p "Nombre del usuario: " userName
		if [ $(cat /etc/passwd | cut -f1 -d':' | grep -c $userName) != 0 ]
		then
			echo "El usuario $userName existe"
		else	
			echo "El usuario $userName no existe"
		fi
		;;
		4)
		read -p "Ingrese el nombre del grupo: " groupName
		if [ $(cat /etc/group | cut -f1 -d':' | grep -c $groupName) != 0 ]
		then
			echo "El grupo $groupName ya existe"
		else
			sudo groupadd $groupName
			echo "Grupo $groupName creado"
		fi
		;;
		5)
		read -p "Ingrese nombre del grupo: " groupName
		if [ $(cat /etc/group | cut -f1 -d':' | grep -c $groupName) != 0 ]
		then
			sudo delgroup $groupName
		else
			echo "El grupo $groupName no existe"
		fi
		;;
		6)
		read -p "Ingrese nombre de usuario: " userName
		if [ $(cat /etc/passwd | cut -f1 -d':' | grep -c $userName) != 0 ]
		then
			read -p "Ingrese nombre de grupo: " groupName
			if [ $(cat /etc/group | cut -f1 -d':' | grep -c $groupName) != 0 ]
			then	
				sudo usermod -a -G $groupName $userName
				echo "Usuario $userName agregado al grupo $groupName"
			else
				echo "El grupo no existe"
			fi
		else
			echo "El nombre de usuario no existe"
		fi		
		;;
		7)
		read -p "Ingrese nombre de usuario: " userName
		if [ $(cat /etc/passwd | cut -f1 -d':' | grep -c $userName) != 0 ]
		then
			read -p "Ingrese nombre de grupo: " groupName
			if [ $(cat /etc/group | cut -f1 -d':' | grep -c $groupName) != 0 ]
			then	
				sudo gpasswd -d $userName $groupName
			else
				echo "El grupo no existe"
			fi
		else
			echo "El nombre de usuario no existe"
		fi		
		;;
		8)
		cat /etc/passwd | cut -f1 -d':' 
		;;
		9)
		cat /etc/group | cut -f1 -d':'
		;;
		10)
		exit
		;;
		*)
		echo "Opcion incorrecta"
		;;
	esac
done

