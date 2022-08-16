import React, {useEffect} from "react";
import { Formik } from "formik";
import Imagen from "./../img/Obreros.jpg";
import { Animated } from "react-animated-css";

const ContactPage = () => {
  useEffect(() => {
    window.scroll(0, 0)
  }, [])
  return (
    <>
      <div className="form-container">
        <img className="form-img" src={Imagen}></img>
        <Animated
          animationIn="slideInRight"
          animationOut="fadeOut"
          animationInDuration="500"
          isVisible={true}
        >
          <Formik>
            <form className="form">
              <h1>Envianos tu mensaje</h1>
              <div>
                <input placeholder="Asunto"></input>
              </div>
              <div>
                <textarea placeholder="Mensaje" rows="7" cols="60"></textarea>
              </div>
              <button className="link" to={"/register"}>
                Enviar
              </button>
            </form>
          </Formik>
        </Animated>
      </div>
    </>
  );
};

export default ContactPage;
