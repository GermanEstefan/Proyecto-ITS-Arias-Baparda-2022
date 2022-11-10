/** @format */

import React, { useRef } from "react";
import CategoriesList from "../../components/store/CategoriesList";
import facebookIcon from "../../assets/img/facebook-brands.svg";
import instagramIcon from "../../assets/img/instagram-brands.svg";
import messageIcon from "../../assets/img/message-solid.svg";
import whatsappIcon from "../../assets/img/whatsapp.svg";
import { useMediaQuery } from "react-responsive";
import { useNavigate, Link } from "react-router-dom";
import ContainerBase from "../../components/store/ContainerBase";

const Home = () => {
  const isMobile = useMediaQuery({ query: "(max-width: 800px)" });
  const navigate = useNavigate();
  const productsView = useRef();

  const goToProductsView = () => {
    productsView.current.scrollIntoView();
  };

  return (
    <ContainerBase>
      <main className="home-page main-client">
        <section className="home-page__banner">
          <div className="home-page__banner__info">
            <h1>Seguridad Corporal</h1>
            <p>Lideres del mercado, excelente calidad y precio, somos tu mejor opcion.</p>
            <button onClick={goToProductsView}>Ver categorías</button>
          </div>

          {!isMobile && (
            <div className="home-page__banner__contact">
              <strong>¡ Contactanos !</strong>
              <div>
                <a
                  href="https://www.facebook.com/people/Natalia-Viera-Seguridad-Corporal/100076407723343/"
                  target={"_blank"}
                >
                  <img src={facebookIcon} alt="facebook" />
                </a>
                <a href="https://www.instagram.com/seguridadcorporal/" target={"_blank"}>
                  <img src={instagramIcon} alt="instagram" />
                </a>

                <Link to="/contact">
                  <img src={messageIcon} alt="message" />
                </Link>
                <a href="">
                  <img src={whatsappIcon} alt="whatsapp" />
                </a>
              </div>
            </div>
          )}
        </section>
        <h1 className="home-page__title" ref={productsView}>
          Categorías de productos
        </h1>
        <CategoriesList />
      </main>
    </ContainerBase>
  );
};

export default Home;
