import React from "react";
import Carrito from "./../img/Carrito.jpg";
import LogoCliente from "./../img/Logo-nombre.jpg";
import Button from "./Button";
import { Link } from "react-router-dom";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faShoppingCart } from "@fortawesome/free-solid-svg-icons";
import { useNavigate } from "react-router-dom";
const Navbar = () => {

  const navigate = useNavigate()
const goTo = (path) => {
    navigate(path)
  }
  return (
    <header>
      <div className="navbar">
        <Link to={'/'}>
          <img src={LogoCliente} width="220px" alt="Logo de la empresa" />
        </Link>
        <nav>
          <ul>
            <li>
              
              <Button
              onClick={() => goTo('/contact')  }
                text="Contacto"
              ></Button>
            </li>
            <li>
            <Button
                onClick={() => goTo('/login')  }
                text="Ingresar"
              ></Button>
            </li>
            <li>
              <Button
                onClick={() => goTo('/register')  }
                text="Registrarse"
              ></Button>
            </li>
            <li>
              <Link to={'/'}><FontAwesomeIcon className="icon" icon={faShoppingCart}/></Link>
            </li>
          </ul>
        </nav>
      </div>
    </header>
  );
};

export default Navbar;
