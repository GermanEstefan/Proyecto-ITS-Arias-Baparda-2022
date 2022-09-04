import React from "react";
import LogoCliente from "../../assets/img/Cliente-nombre1.svg";
import { Link } from "react-router-dom";
import { useMediaQuery } from "react-responsive";
import NavMobile from "./NavMobile";
import NavDesktop from "./NavDesktop";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faBars } from "@fortawesome/free-solid-svg-icons";
import { useState } from "react";
import { useRef } from "react";

const Header = () => {

  const isMobile = useMediaQuery({ query: "(max-width: 800px)" });
  const [openMenu, setOpenMenu] = useState(false);

  const navMobileRef = useRef(null);
  const shadowCloseRef = useRef(null);
  const handleCloseMenu = () => {
    navMobileRef.current.classList.add('close-menu');
    shadowCloseRef.current.classList.add('fade-out');
    navMobileRef.current.addEventListener('animationend', () => setOpenMenu(false));
  }

  return (
    <>
      <header className="hedaer-store">
        <Link to='/' className="hedaer-store__logo">
          <img src={LogoCliente} alt="Logo de la empresa" />
        </Link>
          {
            isMobile ?
            <FontAwesomeIcon
              icon={faBars} 
              className="hedaer-store__hamb"
              onClick={() => setOpenMenu(true)} 
            /> 
            :
            <NavDesktop/>
          }
      </header>
      { isMobile && <NavMobile openMenu={openMenu} setOpenMenu={setOpenMenu} refMenu = {navMobileRef} /> }
      { (isMobile && openMenu) && <div onClick={handleCloseMenu} ref={shadowCloseRef} className="shadow-close"></div> }
    </>
  );

};

export default Header;
