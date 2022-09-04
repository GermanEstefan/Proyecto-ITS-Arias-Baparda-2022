import { faUser, faRightFromBracket, faGear, faHistory } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import React from "react";
import { useNavigate } from "react-router-dom";

const UserMenu = ({ visiblity, setVisiblity, name, surname }) => {

    const navigate = useNavigate();

    const handleToggleUserMenu = () => {
        setVisiblity(!visiblity);
    }

    const handleLogout = () => {
        localStorage.removeItem('token');
        window.location.reload();
        navigate('/')
    }
 
    return (
        <div className="user-menu">
            <FontAwesomeIcon icon={faUser} onClick={handleToggleUserMenu} />
            {
                visiblity &&
                <div>
                    <strong>{`${name} ${surname}`}</strong>
                    <ul>
                        <li>
                            <span>Transacciones</span><FontAwesomeIcon icon={faHistory} onClick={() => alert('En proceso de implementacion...')} />
                        </li>
                        <li>
                            <span>Configuracion</span><FontAwesomeIcon icon={faGear} onClick={() => alert('En proceso de implementacion...')}/> 
                        </li>
                        <li>
                            <span>Salir</span><FontAwesomeIcon onClick={handleLogout} icon={faRightFromBracket} /> 
                        </li>
                    </ul>
                </div>
            }
        </div>
    )

}

export default UserMenu;