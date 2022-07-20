opt=0
while [ "$opt" != "10" ]
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
		clear
		read -p "Nombre de usuario: " userName
		if [ -z "$userName" ]
			then
			clear
			echo "No se admiten valores vacios!"
			continue
		fi
		if [ $(cat /etc/passwd | cut -f1 -d':' | grep -c -w $userName) != 0 ]
		then
			clear
			echo "El usuario $userName ya existe"
			continue
		else
			clear
			sudo useradd $userName
			echo "Usuario $userName creado"
		fi
		read -p "¿Desea asignarle una contraseña? y | n " asignPasswd
		if [ $asignPasswd = "y" ]
		then
			clear
			sudo passwd $userName
			echo "Contraseña creada"
		elif [ $asignPasswd = "n" ]
		then
			clear
			continue
		else
			clear
			echo "Opcion incorrecta"
			continue
		fi
		;;

		2)
		clear
		read -p "Ingrese el nombre del usuario: " userName
		if [ -z "$userName" ]
			then
			echo "No se admiten valores vacios!"
			continue
		fi
		if [ $(cat /etc/passwd | cut -f1 -d':' | grep -c -w $userName) != 0 ]
		then
			clear
			sudo deluser $userName
		else
			clear
			echo "El usuario $userName no existe"
		fi
		;;

		3)
		clear
		read -p "Nombre de usuario a modificar: " userName
		if [ -z "$userName" ]
			then
			echo "No se admiten valores vacios!"
			continue
		fi
		if [ $(cat /etc/passwd | cut -f1 -d':' | grep -c -w $userName) != 0 ]
		then
			while [ "$opt" != "z" ]
				do
				echo "Seleccione una opcion"
				echo "A- Bloquear contraseña de $userName"
				echo "B- Desbloquear contraseña de $userName"
				echo "C- Modificar contraseña de $userName"
				echo "D- Agregar $userName a un grupo"
				echo "E- Eliminar $userName de un grupo"
				echo "Z- Volver"
				read opt
				case $opt in
					a)
					clear
					sudo usermod -L $userName
					echo "Contraseña bloqueada correctamente: "
					sudo cat /etc/shadow | grep -w $userName
					;;
					b)
					clear
					sudo usermod -U $userName
					echo "Contraseña desbloqueada correctamente: "
					sudo cat /etc/shadow | grep -w $userName
					;;
					c)
					clear
					sudo passwd $userName
					;;
					d)
					clear
					read -p "Ingrese nombre de grupo: " groupName
					if [ -z "$groupName" ]
						then
						echo "No se admiten valores vacios!"
						continue
					fi
					if [ $(cat /etc/group | cut -f1 -d':' | grep -c -w $groupName) != 0 ]
						then	
						sudo usermod -a -G $groupName $userName
						echo "Usuario $userName agregado al grupo $groupName"
						sudo cat /etc/group | grep -w $groupName
					else
						echo "El grupo no existe"
					fi
					;;
					e)
					clear
					read -p "Ingrese nombre de grupo: " groupName
					if [ -z "$groupName" ]
						then
						echo "No se admiten valores vacios!"
						continue
					fi
					if [ $(cat /etc/group | cut -f1 -d':' | grep -c -w $groupName) != 0 ]
						then	
						sudo gpasswd -d $userName $groupName
						echo "Usuario $userName eliminado de $groupName correctamente"
						sudo cat /etc/group | grep -w $groupName
					else
						echo "El grupo no existe"
					fi	
					;;
					z)
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
			echo "El Usuario $userName no existe"
			continue
		fi
		;;

		4)
		clear
		sudo cat /etc/passwd | cut -f1 -d':' 
		sleep 2
		;;

		5)
		clear
		read -p "Nombre del usuario: " userName
		if [ -z "$userName" ]
			then
			echo "No se admiten valores vacios!"
			continue
		fi
		if [ $(cat /etc/passwd | cut -f1 -d':' | grep -c -w $userName) != 0 ]
		then
			echo "El usuario $userName existe"
		else
			echo "El usuario $userName no existe"
		fi
		;;

		6)
		clear
		read -p "Ingrese el nombre del grupo: " groupName
		if [ -z "$groupName" ]
			then
			echo "No se admiten valores vacios!"
			continue
		fi
		if [ $(cat /etc/group | cut -f1 -d':' | grep -c -w $groupName) != 0 ]
		then
			echo "El grupo $groupName ya existe"
		else
			sudo groupadd $groupName
			echo "Grupo $groupName creado"
			sudo cat /etc/group | grep -w $groupName
		fi
		;;

		7)
		clear
		read -p "Ingrese nombre del grupo: " groupName
		if [ -z "$groupName" ]
			then
			echo "No se admiten valores vacios!"
			continue
		fi
		if [ $(cat /etc/group | cut -f1 -d':' | grep -c -w $groupName) != 0 ]
		then
			sudo delgroup $groupName
			echo "El grupo $groupName fue eliminado correctamente"
		else
			echo "El grupo $groupName no existe"
		fi
		;;

		8)
		clear
		read -p "Ingrese nombre de grupo: " groupName
		if [ -z "$groupName" ]
			then
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
					clear
					read -p "Ingrese el nuevo GID del grupo " gid
					if [ -z "$gid" ]
						then
						echo "No se admiten valores vacios"
						sleep 2
						continue
					fi
					re='^[0-9]+$'
					if ! [[ $gid =~ $re ]] ; then
   						echo "Solo se admiten valores numericos"
						sleep 2
						continue
					fi
					if [ $(cat /etc/group | cut -f3 -d':' | grep -c -w $gid) != 0 ]
						then
						echo "El GID $gid ya se encuentra en uso"
						sleep 2
						continue
					fi
					sudo groupmod -g $gid $groupName
					echo "El GID se cambio con exito"
					sudo cat /etc/group | grep -w $groupName
					sleep 3
					;;
					b)
					read -p "Ingrese el nuevo nombre " newName
					if [ -z "$newName" ]
						then
						echo "No se admiten valores vacios"
						sleep 2
						continue
					fi
					sudo groupmod -n $newName $groupName
					echo "Nombre de grupo cambiado con exito"
					sleep 2
					;;
					z)
					clear
					opt=z
					;;
					*)
					echo "Opcion invalida"
					;;
				esac
			done
		else
			echo "El grupo no existe"
		fi	
		;;
		9)
		clear
		sudo cat /etc/group | cut -f1 -d':'
		sleep 2
		;;

		10)
		clear
		exit
		;;

		*)
		clear
		echo "Opcion incorrecta"
		;;

	esac

done
