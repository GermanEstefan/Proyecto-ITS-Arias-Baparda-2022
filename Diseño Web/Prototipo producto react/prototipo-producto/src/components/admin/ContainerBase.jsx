import { faGear } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import React, { useContext } from "react";
import { userStatusContext } from "../../App";
import { capitalizeString } from "../../helpers/capitalizeString";
import Aside from "./Aside";

const ContainerBase = ({ children }) => {

    const { userData } = useContext(userStatusContext);
    const { name, surname, rol } = userData;

    return (
        <>
            <header className="header-admin">
                <div className="header-admin_config">
                    <FontAwesomeIcon icon={faGear} />
                    <div>
                        <span>{capitalizeString(`${name} ${surname}`)}</span>
                        <small>{capitalizeString(rol)}</small>
                    </div>
                </div>
            </header>

            <main className="container-admin">
                <Aside />
                {!children ? <section><h1>Elige una opcion</h1></section> : children }
            </main>


        </>
    )
}

export default ContainerBase;