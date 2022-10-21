#!bin/bash
logger -p local1.info "Ingresando a menu de administracion de usuarios y grupos"
opt=0
while [ "$opt" != 10 ]
do
	echo "Selecciona una opcion"
	echo "-----------------------------------"
	echo "-------------USUARIOS--------------"
	echo "-----------------------------------"
	echo "1- Agregar un usuario"
	echo "2- Eliminar un usuario"
	echo "3- Modificar un usuario"
	echo "4- Listar usuarios"
	echo "5- Verificar existencia de usuario"
	echo "-----------------------------------"
	echo "--------------GRUPOS---------------"
	echo "-----------------------------------"
	echo "6- Agregar un nuevo grupo"
	echo "7- Eliminar un grupo"
	echo "8- Modificar un grupo"
	echo "9- Listar grupos"
	echo "10- Salir"
	read opt
	case $opt in
		1)
		logger -p local1.info "Ingresando en la opcion de agregar usuarios"
		clear
		read -p "Nombre de usuario: " userName
		if [ -z "$userName" ]
			then
			logger -p local1.info "...ingreso campos vacios, error"
			clear
			echo "No se admiten valores vacios!"
			continue
		fi
		if [ $(cat /etc/passwd | cut -f1 -d':' | grep -c -w $userName) != 0 ]
		then
			clear
			logger -p local1.info "...el usuario que quiere ingresar ya existe, error"
			echo "El usuario $userName ya existe"
			continue
		else
			logger -p local1.info "...usuario creado sin contraseña"
			clear
			sudo useradd $userName
			echo "Usuario $userName creado"
		fi
		read -p "¿Desea asignarle una contraseña? y | n " asignPasswd
		if [ $asignPasswd = "y" ]
		then
			logger -p local1.info "...agregando contraseña"
			clear
			sudo passwd $userName
			echo "Contraseña creada"
		elif [ $asignPasswd = "n" ]
		then
			logger -p local1.info "...usuario creado sin asignar contraseña"
			clear
			continue
		else
			clear
			echo "Opcion incorrecta"
			continue
		fi
		;;
		2)
		logger -p local1.info "ingreso a opcion de eliminar usuario"
		clear
		read -p "Ingrese el nombre del usuario: " userName
		if [ -z "$userName" ]
			then
			logger -p local1.info "...intento ingresar campos vacios, error"
			echo "No se admiten valores vacios!"
			continue
		fi
		if [ $(cat /etc/passwd | cut -f1 -d':' | grep -c -w $userName) != 0 ]
		then
			logger -p local1.indo "...usuario eliminado"
			clear
			sudo deluser $userName
		else
			logger -p local1.info "...el usuario que quieres eliminar no existe, error"
			clear
			echo "El usuario $userName no existe"
		fi
		;;
		3)
		logger -p local1.info "Ingresando a la opcion de modificar usuario"
		clear
		read -p "Nombre de usuario a modificar: " userName
		if [ -z "$userName" ]
			then
			logger -p local1.info "Intento ingresar campos vacios, error"
			echo "No se admiten valores vacios!"
			continue
		fi
		if [ $(cat /etc/passwd | cut -f1 -d':' | grep -c -w $userName) != 0 ]
		then
			logger -p local1.info "..modificando usuario"
			opt2=a
			while [ "$opt2" != "z" ]
				do
				echo "Seleccione una opcion"
				echo "A- Bloquear contraseña de $userName"
				echo "B- Desbloquear contraseña de $userName"
				echo "C- Modificar contraseña de $userName"
				echo "D- Agregar $userName a un grupo"
				echo "E- Eliminar $userName de un grupo"
				echo "Z- Volver"
				read opt2
				case $opt2 in
					a)
					logger -p local1.info "intentando bloquear contraseña de usuario"
					clear
					sudo usermod -L $userName
					if [ $? == 0 ]
					then
						logger -p local1.info "...contrasela bloqueada"
						echo "Contraseña bloqueada correctamente: "
						sudo cat /etc/shadow | grep -w $userName
					else
						logger -p local1.info "...no se pudo bloquear la contraseña"
						echo "No se pudo bloquear la contraseña"
					fi
					;;
					b)
					logger -p local1.info "intentando desbloquear la constraseña del usuario"
					clear
					sudo usermod -U $userName
					if [ $? == 0 ]
					then
						logger -p local1.info "...contraseña desbloqueada"
						echo "Contraseña desbloqueada correctamente: "
						sudo cat /etc/shadow | grep -w $userName
					else
						logger -p local1.info "...no se pudo desbloquear la contraseña"
						echo "No se pudo desbloquear la contraseña"
					fi
					;;
					c)
					logger -p local1.info "Intentando modificar la contraseña"
					clear
					sudo passwd $userName
					if [ $? == 0 ]
					then
						logger -p local1.info "...contraseña modificada exitosamente"
						echo "exito al modificar"
						sudo cat /etc/shadow | grep -w $userName
					else
						logger -p local1.info "...no se pudo modificar la contraseña"
						echo "No se pudo modificar la contraseña"
					fi
					;;
					d)
					logger -p local1.info "intentando agregar usuario a un grupo"
					clear
					read -p "Ingrese nombre de grupo: " groupName
					if [ -z "$groupName" ]
					then
						logger -p local1.info "...intentando ingresar valores vacios, error"
						echo "No se admiten valores vacios!"
						continue
					fi
					if [ $(cat /etc/group | cut -f1 -d':' | grep -c -w $groupName) != 0 ]
					then	
						logger -p local1.info "...usuario '$userName' ingresado al grupo '$groupName'"
						sudo usermod -a -G $groupName $userName
						echo "Usuario $userName agregado al grupo $groupName"
						sudo cat /etc/group | grep -w $groupName
					else
						logger -p local1.info "...el grupo que ingreso no existe"
						echo "El grupo no existe"
					fi
					;;
					e)
					logger -p local1.info "Intentando quitar un usuario a un grupo"
					clear
					read -p "Ingrese nombre de grupo: " groupName
					if [ -z "$groupName" ]
						then
						logger -p local1.info "...intentando ingresar valores vacios, error"
						echo "No se admiten valores vacios!"
						continue
					fi
					if [ $(cat /etc/group | cut -f1 -d':' | grep -c -w $groupName) != 0 ]
						then	
						logger -p local1.info "...el usuario '$userName' fue eliminado del grupo '$groupName'"
						sudo gpasswd -d $userName $groupName
						echo "Usuario $userName eliminado de $groupName correctamente"
						sudo cat /etc/group | grep -w $groupName
					else
						logger -p local1.info "...el grupo que ingreso no existe"
						echo "El grupo no existe"
					fi	
					;;
					z)
					logger -p local1.info "Saliendo del menu de modificacion de usuario"
					clear
					opt=z
					;;
					*)
					clear
					echo "Opcion invalida"
					;;
				esac 
			done
		else
			logger -p local1.info "el usuario que ingreso no existe"
			echo "El Usuario $userName no existe"
			continue
		fi
		;;
		4)
		logger -p local1.info "ingreso a la opcion de listar usuarios"
		clear
		sudo cat /etc/passwd | cut -f1 -d':' 
		if [ $? == 0 ]
		then
			logger -p local1.info "...exito al listar los usuarios"
		else
			logger -p local1.info "...no se pudieron listar los usuarios"
		fi
		sleep 2
		;;
		5)
		logger -p local1.info "ingreso a la opcion de verificar la existencia de un usuario"
		clear
		read -p "Nombre del usuario: " userName
		if [ -z "$userName" ]
			then
			logger -p local1.info "...intentando ingresar valores vacios, error"
			echo "No se admiten valores vacios!"
			continue
		fi
		if [ $(cat /etc/passwd | cut -f1 -d':' | grep -c -w $userName) != 0 ]
		then
			logger -p local1.info "...se verifico la existencia del usuario '$userName'"
			echo "El usuario $userName existe"
		else
			logger -p local1.info "...el usuario $userName no existe en el sistema"
			echo "El usuario $userName no existe"
		fi
		;;
		6)
		logger -p local1.info "ingreso a la opcion de agregar un nuevo grupo"
		clear
		read -p "Ingrese el nombre del grupo: " groupName
		if [ -z "$groupName" ]
			then
			logger -p local1.info "...intentando ingresar valores vacios, error"
			echo "No se admiten valores vacios!"
			continue
		fi
		if [ $(cat /etc/group | cut -f1 -d':' | grep -c -w $groupName) != 0 ]
		then
			logger -p local1.info "...el grupo que esta intentando crear ya existe"
			echo "El grupo $groupName ya existe"
		else
			logger -p local1.info "...grupo creado con exito"
			sudo groupadd $groupName
			echo "Grupo $groupName creado"
			sudo cat /etc/group | grep -w $groupName
		fi
		;;
		7)
		logger -p local1.info "Ingreso de opcion para eliminar grupo"
		clear
		read -p "Ingrese nombre del grupo: " groupName
		if [ -z "$groupName" ]
			then
			logger -p local1.info "intentando ingresar valores vacios, error"
			echo "No se admiten valores vacios!"
			continue
		fi
		if [ $(cat /etc/group | cut -f1 -d':' | grep -c -w $groupName) != 0 ]
		then
			logger -p local1.info "...grupo '$groupName' eliminado"
			sudo delgroup $groupName
			echo "El grupo $groupName fue eliminado correctamente"
		else
			logger -p local1.info "...el grupo que quiere eliminar no exite"
			echo "El grupo $groupName no existe"
		fi
		;;
		8)
		logger -p local1.info "Ingreso a la opcion de modificar grupo"
		clear
		read -p "Ingrese nombre de grupo: " groupName
		if [ -z "$groupName" ]
			then
			logger -p local1.info "...intentando ingresar valores vacios"
			echo "No se admiten valores vacios!"
			continue
		fi
		if [ $(cat /etc/group | cut -f1 -d':' | grep -c -w $groupName) != 0 ]
			then	
			while [ "$opt" != "z" ]
				do
				clear
				echo "Seleccione una opcion"
				echo "A - Cambiar GID del grupo $groupName"
				echo "B - Cambiar nombre del grupo $groupName"
				echo "Z- Volver"
				read opt
				case $opt in
					a)
					logger -p local1.info "...intentando cambiar el GID del grupo"
					clear
					read -p "Ingrese el nuevo GID del grupo " gid
					if [ -z "$gid" ]
						then
						local1.info "...intentando ingresar campos vacios, error"
						echo "No se admiten valores vacios"
						sleep 2
						continue
					fi
					re='^[0-9]+$'
					if ! [[ $gid =~ $re ]] ; then
						logger -p local1.info "...intentando ingresar valores numericos, error"
   						echo "Solo se admiten valores numericos"
						sleep 2
						continue
					fi
					if [ $(cat /etc/group | cut -f3 -d':' | grep -c -w $gid) != 0 ]
						then
						logger -p local1.info "...el valor del GID ya se encuentra en uso"
						echo "El GID $gid ya se encuentra en uso"
						sleep 2
						continue
					fi
					logger -p local1.info "...valor del GID actualizado con exito"
					sudo groupmod -g $gid $groupName
					echo "El GID se cambio con exito"
					sudo cat /etc/group | grep -w $groupName
					sleep 3
					;;
					b)
					logger -p local1.info "Intentando cambiar el nombre del grupo"
					read -p "Ingrese el nuevo nombre " newName
					if [ -z "$newName" ]
						then
						logger -p local1.info "...ingresando valores vacios, error"
						echo "No se admiten valores vacios"
						sleep 2
						continue
					fi
					logger -p local1.info "...el nombre del grupo fue cambiado"
					sudo groupmod -n $newName $groupName
					echo "Nombre de grupo cambiado con exito"
					sleep 2
					;;
					z)
					logger -p local1.info "Saliendo del menu de modificar grupo" 
					clear
					opt=z
					;;
					*)
					echo "Opcion invalida"
					;;
				esac
			done
		else
			logger -p local1.info "el grupo que ingreso no existe"
			echo "El grupo no existe"
		fi	
		;;
		9)
		logger -p local1.info "ingresando a la opcion de listar grupos"
		clear
		sudo cat /etc/group | cut -f1 -d':'
		if [ $? == 0 ]
		then
			logger -p local1.info "...grupos listados con exito"
		else
			logger -p local1.info "...no se pudieron listar los grupos"
		fi
		sleep 2
		;;
		10)
		logger -p local1.info "Saliendo del menu de aministracion de usuarios y grupos"
		clear
		exit
		;;
		*)
		clear
		echo "Opcion incorrecta"
		;;
	esac
done
