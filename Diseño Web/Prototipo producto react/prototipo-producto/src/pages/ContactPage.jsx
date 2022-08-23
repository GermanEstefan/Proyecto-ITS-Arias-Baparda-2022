import React, {useEffect} from "react";
import Imagen from "./../img/Obreros.jpg";
import { Animated } from "react-animated-css";

const ContactPage = () => {
  useEffect(() => {
    window.scroll(0, 0);
  }, []);
  return (
    <>
      <div className="form-container">
        <img className="form-img" src={Imagen} alt='Imagen'></img>
        <Animated
          animationIn="slideInRight"
          animationOut="fadeOut"
          isVisible={true}
        >
       
              <form className="form">
                <h1>Envianos tu mensaje</h1>
                <div>
                  <input name="topic" id="topic"  placeholder="Asunto"></input>
                </div>
                <div>
                  <textarea
                    name="message"
                    id="message"
                    rows={7}
                    maxLength={130}
                    placeholder="Mensaje"
                  ></textarea>
                  <p>/130</p>
                </div>
                <button className="link" to={"/register"}>
                  Enviar
                </button>
              </form>
        </Animated>
      </div>
    </>
  );
};

export default ContactPage;
