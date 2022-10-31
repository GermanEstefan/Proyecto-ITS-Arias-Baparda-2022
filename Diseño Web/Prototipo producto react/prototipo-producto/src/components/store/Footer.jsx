import React from "react";
import facebookIcon from "../../assets/img/facebook-brands.svg";
import instagramIcon from "../../assets/img/instagram-brands.svg";
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
        <a href="www.google.com" target="_blank" rel="noopener noreferrer">
          <FontAwesomeIcon icon={faFacebook} size="3x" color="white" />
        </a>
        <a href="www.google.com" target="_blank" rel="noopener noreferrer">
          <FontAwesomeIcon icon={faInstagram} size="3x" color="white" />
        </a>
      </div>
    </footer>
  );
};

export default Footer;
