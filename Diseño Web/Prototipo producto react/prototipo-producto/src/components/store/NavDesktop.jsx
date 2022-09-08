import { faUser } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import React from "react";
import { useState } from "react";
import { useContext } from "react";
import { Link, useNavigate } from "react-router-dom";
import { userStatusContext } from "../../App";
import ShoppingCart from "./ShoppingCart";

const NavDesktop = () => {

    const {userData} = useContext(userStatusContext);
    const [openUserMenu, setOpenUserMenu] = useState(false);
    const navigate  = useNavigate();

    return (
        <nav className="header-store__nav-desktop">
            <ul>
                {
                    userData.auth
                    ?
                    <FontAwesomeIcon icon={faUser} onClick={() => navigate('/panel-user') } />
                    :
                    <>
                        <Link to='/login'><li>Ingresar</li></Link>
                        <Link to='/register'><li>Registrarse</li></Link>
                    </>
                }
                <ShoppingCart/>
            </ul>
        </nav>
    )
}

export default NavDesktop;

