/** @format */

import React from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faInstagram, faFacebook } from "@fortawesome/free-brands-svg-icons";

const Footer = () => {
  return (
    <footer className="footer">
      <ul>
        <li>seguridadcorporal@gmail.com</li>
        <li>092 065 001</li>
        <li>Moltke 1194, Montevideo</li>
      </ul>
      <div className="img-container">
        <a
          href="https://www.instagram.com/seguridadcorporal/"
          target="_blank"
          rel="noopener noreferrer"
        >
          <FontAwesomeIcon icon={faFacebook} size="3x" color="white" />
        </a>
        <a
          href="https://www.facebook.com/people/Natalia-Viera-Seguridad-Corporal/100076407723343/"
          target="_blank"
          rel="noopener noreferrer"
        >
          <FontAwesomeIcon icon={faInstagram} size="3x" color="white" />
        </a>
      </div>
    </footer>
  );
};

export default Footer;
