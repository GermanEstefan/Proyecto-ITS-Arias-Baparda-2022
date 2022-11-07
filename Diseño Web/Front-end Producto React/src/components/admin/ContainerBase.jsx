import { faRightFromBracket } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import React, { useContext } from "react";
import { useNavigate } from "react-router-dom";
import { userStatusContext } from "../../App";
import { capitalizeString } from "../../helpers/capitalizeString";
import SelectAnyOptionScreen from "../../pages/admin/SelectAnyOptionScreen";
import Aside from "./Aside";

const ContainerBase = ({ children }) => {

    const navigate = useNavigate();
    const { userData, setUserData } = useContext(userStatusContext);
    const { name, surname, rol } = userData;

    const handleLogout = () => {
        localStorage.removeItem('token');
        setUserData({
            address: null,
            auth: false,
            ci: null,
            email: null,
            name: null,
            phone: null,
            rol: null,
            surname: null
        })
        navigate('/admin/login');
    }

    return (
        !(rol)
        ?
        <h1>Permiso denegado</h1>
        :
        <>
            <header className="header-admin">
                <div className="header-admin_config">
                    <FontAwesomeIcon icon={faRightFromBracket} onClick={handleLogout} />
                    <div>
                        <span>{ (name && surname) && capitalizeString(`${name} ${surname}`)}</span>
                        <small>{ rol && capitalizeString(rol)}</small>
                    </div>
                </div>
            </header>

            <main className="container-admin">
                <Aside />
                {!children ? <SelectAnyOptionScreen/> : children }
            </main>


        </>
    )
}

export default ContainerBase;