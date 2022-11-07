/** @format */

import React from "react";
import LogoCliente from "../../assets/img/Cliente-nombre1.svg";
import { Link, useNavigate } from "react-router-dom";
import { useMediaQuery } from "react-responsive";
import NavMobile from "./NavMobile";
import NavDesktop from "./NavDesktop";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faBars, faSearch } from "@fortawesome/free-solid-svg-icons";
import { useState } from "react";
import { useRef } from "react";

const Header = () => {
  const isMobile = useMediaQuery({ query: "(max-width: 800px)" });
  const [openMenu, setOpenMenu] = useState(false);
  const navigate = useNavigate();
  const navMobileRef = useRef(null);
  const shadowCloseRef = useRef(null);
  const [searchData, setSearchData] = useState("");
  const handleCloseMenu = () => {
    navMobileRef.current.classList.add("close-menu");
    shadowCloseRef.current.classList.add("fade-out");
    navMobileRef.current.addEventListener("animationend", () => setOpenMenu(false));
  };

  const handleSearch = (e) => {
    
    searchData !== "" && navigate(`/results/${searchData}`);
  };

  return (
    <>
      <header className="header-store">
        <Link to="/" className="header-store__logo">
          <img src={LogoCliente} alt="Logo de la empresa" />
        </Link>
        {!isMobile && (
          <form className="header-store__search" onSubmit={(e) => handleSearch(e)}>
            <div>
              <FontAwesomeIcon icon={faSearch} />
              <input
                type="text"
                placeholder="Buscar productos"
                onChange={(e) => setSearchData(e.target.value)}
              />
            </div>
          </form>
        )}
        {isMobile ? (
          <FontAwesomeIcon
            icon={faBars}
            className="header-store__hamb"
            onClick={() => setOpenMenu(true)}
          />
        ) : (
          <NavDesktop />
        )}
      </header>
      {isMobile && (
        <NavMobile openMenu={openMenu} setOpenMenu={setOpenMenu} refMenu={navMobileRef} />
      )}
      {isMobile && openMenu && (
        <div onClick={handleCloseMenu} ref={shadowCloseRef} className="shadow-close"></div>
      )}
    </>
  );
};

export default Header;
