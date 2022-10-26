import React, { useRef } from "react";
import CategoriesList from "../../components/store/CategoriesList";
import facebookIcon from "../../assets/img/facebook-brands.svg";
import instagramIcon from "../../assets/img/instagram-brands.svg";
import messageIcon from "../../assets/img/message-solid.svg";
import whatsappIcon from "../../assets/img/whatsapp.svg";
import { useMediaQuery } from "react-responsive";
import { useNavigate } from "react-router-dom";
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
            <p>
              Lideres del mercado, excelente calidad y precio, somos tu mejor
              opcion.
            </p>
            <button onClick={goToProductsView}>Ver productos</button>
          </div>

          {!isMobile && (
            <div className="home-page__banner__contact">
              <strong>¡ Contactanos !</strong>
              <div>
                <img src={facebookIcon} alt="facebook" />
                <img src={instagramIcon} alt="instagram" />
                <img
                  src={messageIcon}
                  alt="message"
                  onClick={() => navigate("/contact")}
                />
                <img src={whatsappIcon} alt="whatsapp" />
              </div>
            </div>
          )}
        </section>
        <h1 className="home-page__title" ref={productsView}>
          Categorias
        </h1>
        <CategoriesList />
      </main>
    </ContainerBase>
  );
};

export default Home;
