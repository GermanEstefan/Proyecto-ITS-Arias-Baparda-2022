import React from "react";
import LogoCliente from "./../img/Cliente-nombre1.svg";
import Button from "./Button";
import { Link } from "react-router-dom";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faShoppingCart } from "@fortawesome/free-solid-svg-icons";
import { useNavigate } from "react-router-dom";
import {useMediaQuery} from "react-responsive";
const Navbar = () => {
  const navigate = useNavigate();
  const goTo = (path) => {
    navigate(path);
  };

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
                <Button onClick={() => goTo("/login")} text="Ingresar"></Button>
              </li>
              <li>
                <Button
                  onClick={() => goTo("/register")}
                  text="Registrarse"
                ></Button>
              </li>
              <li>
                <Link to={"/"}>
                  <FontAwesomeIcon className="icon" icon={faShoppingCart} />
                </Link>
              </li>
            </ul>
          )}
        </nav>
      </div>
    </header>
  );
};

export default Navbar;
