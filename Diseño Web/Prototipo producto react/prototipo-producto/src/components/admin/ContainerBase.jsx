import { faRightFromBracket } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import React, { useContext } from "react";
import { userStatusContext } from "../../App";
import Aside from "./Aside";


const ContainerBase = ({ children }) => {

    const { userData } = useContext(userStatusContext);
    const { name, surname, rol } = userData;

    return (
        <>

            <header className="header-admin">
                
                <span>{name + ' ' + surname + ' - '}
                    <strong>{rol}</strong>
                </span>
                <FontAwesomeIcon icon={faRightFromBracket} className="header-admin_logout" />
            </header>

            <main className="container-admin">
                <Aside />
                {children}
            </main>

        </>
    )
}

export default ContainerBase;