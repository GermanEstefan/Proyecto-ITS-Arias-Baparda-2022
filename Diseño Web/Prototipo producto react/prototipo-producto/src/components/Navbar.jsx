import React, { useContext } from "react";
import LogoCliente from "./../img/Cliente-nombre1.svg";
import Button from "./Button";
import { Link } from "react-router-dom";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faShoppingCart } from "@fortawesome/free-solid-svg-icons";
import { useNavigate } from "react-router-dom";
import { useMediaQuery } from "react-responsive";
import { userStatusContext } from "../App";

const Navbar = () => {
  const { userData } = useContext(userStatusContext);
  const navigate = useNavigate();
  const goTo = (path) => {
    navigate(path);
  };

  const handleLogOut  = () => {
    localStorage.setItem("token", ''); 
    window.location.reload(false);
    console.log('reload')
  }

  const isMobile = useMediaQuery({ query: "(max-width: 1000px)" });

  return (
    <header>
      <div className="navbar">
        <Link to={"/"}>
          <img src={LogoCliente} width="300px" alt="Logo de la empresa" />
        </Link>
        <nav>
          {isMobile ? (
            <p>Futura anvorgesa</p>
          ) : (
            <ul>
              <li>
                <Button
                  onClick={() => goTo("/contact")}
                  text="Contacto"
                ></Button>
              </li>
              <li>
                {!userData.name ? (
                  <Button onClick={() => goTo("/login")} text={"Ingresar"} />
                ) : (
                  <Button onClick={handleLogOut} text={"Salir"} />
                )}
              </li>
              <li>
                <Button
                  onClick={() => goTo("/register")}
                  text="Registrarse"
                ></Button>
              </li>
              {userData.name && <li>
                <Link to={"/"}>
                  <FontAwesomeIcon className="icon" icon={faShoppingCart} />
                </Link>
              </li>}
            </ul>
          )}
        </nav>
      </div>
    </header>
  );
};

export default Navbar;
