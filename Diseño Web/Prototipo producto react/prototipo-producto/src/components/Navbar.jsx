import React from "react";
import Carrito from "./../img/Carrito.jpg";
import LogoCliente from "./../img/Logo-nombre.jpg";
import Button from "./Button";
import { Link } from "react-router-dom";
const Navbar = () => {
  return (
    <header>
      <div className="navbar">
        <Link to={'/'}>
          <img src={LogoCliente} width="220px" alt="Logo de la empresa" />
        </Link>
        <nav>
          <ul>
            <li>
              <Link to={'/contact'}>
              <Button
                text="Contacto"
              ></Button>
              </Link>
            </li>
            <li>
            <Button
                onClick={() => {
                  console.log("Click!");
                }}
                text="Ingresar"
              ></Button>
            </li>
            <li>
              <Button
                onClick={() => {
                  console.log("Click!");
                }}
                text="Registrarse"
              ></Button>
            </li>
            <li>
              <img className="carrito" src={Carrito} width="50px" alt="Carrit" />
            </li>
          </ul>
        </nav>
      </div>
    </header>
  );
};

export default Navbar;
