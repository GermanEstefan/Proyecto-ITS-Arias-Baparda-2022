import React from "react";
import { useState } from "react";
import { useContext } from "react";
import { Link } from "react-router-dom";
import { userStatusContext } from "../../App";
import ShoppingCart from "./ShoppingCart";
import UserMenu from "./UserMenu";

const NavDesktop = () => {

    const {userData} = useContext(userStatusContext);
    const [openUserMenu, setOpenUserMenu] = useState(false);

    return (
        <nav className="header-store__nav-desktop">
            <ul>
                {
                    userData.auth
                    ?
                    <UserMenu 
                        visiblity = {openUserMenu} 
                        setVisiblity={setOpenUserMenu}
                        name={userData.name}
                        surname={userData.surname} 
                    />
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

