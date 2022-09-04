import React, { useContext } from "react";
import { Link, useNavigate } from "react-router-dom";
import { userStatusContext } from "../../App";
import LogoCliente from "../../assets/img/Cliente-nombre1.svg";

const NavMobile = ({ openMenu, refMenu }) => {

    const {userData} = useContext(userStatusContext);
    const navigate = useNavigate();
    const handleLogout = () => {
        localStorage.removeItem('token');
        window.location.reload();
        navigate('/')
    }

    return (
        openMenu &&
        <nav className="nav-mobile-store" ref={refMenu}>
            {
                userData.auth && 
                <strong>{`${userData.name}  ${userData.surname}`}</strong>
            }
            <ul>
                <Link to='/'><li>Inicio</li></Link>
                {
                    userData.auth
                    ?
                    <>
                        <li onClick={() => alert('En proceso de implementacion')}>Transacciones</li>
                        <li onClick={() => alert('En proceso de implementacion')}>Configuracion</li>
                        <li style={{color:'red'}} onClick={handleLogout}>Salir</li>
                    </>
                    :
                    <>
                        <Link to='/contact'><li>Contacto</li></Link>
                        <Link to='/login'><li>Ingresar</li></Link>
                        <Link to='/register'><li>Registrarse</li></Link>
                    </>
                }
                
            </ul>
            <img src={LogoCliente} alt="Logo de la empresa" />
        </nav>
    )
}

export default NavMobile;

