
import { faRightFromBracket } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import React from "react";
import { useContext } from "react";
import { userStatusContext } from "../../App";
import LogoCliente from "../../assets/img/Cliente-nombre1.svg";

const Header = () => {

    const {userData} = useContext(userStatusContext);
    const {name, surname, rol} = userData;

    return(
        <header className="header-admin">
            <img src={LogoCliente} alt="logo" />
            <span>{name+' '+surname+' - '}  
                <strong>{rol}</strong> 
            </span>
            <FontAwesomeIcon icon={faRightFromBracket} className="header-admin_logout"/>
        </header>
    )
}

export default Header;