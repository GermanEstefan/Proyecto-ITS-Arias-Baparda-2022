import React, {useEffect} from "react";
import { Formik } from "formik";
import Imagen from "./../img/Obreros.jpg";
import { Animated } from "react-animated-css";

const ContactPage = () => {
  useEffect(() => {
    window.scroll(0, 0);
  }, []);
  return (
    <>
      <div className="form-container">
        <img className="form-img" src={Imagen}></img>
        <Animated
          animationIn="slideInRight"
          animationOut="fadeOut"
          isVisible={true}
        >
          <Formik
            handleChange={(valores) => {
              console.log(valores);
            }}
            initialValues={{
              topic: "",
              message: "asdasd",
            }}
            handleSubmit={(valores) => {
              console.log("se envió");
              console.log(valores);
            }}
          >
            {({ values, handleChange, handleSubmit }) => (
              <form className="form" onSubmit={handleSubmit}>
                <h1>Envianos tu mensaje</h1>
                <div>
                  <input name="topic" id="topic" value={values.topic} onChange={handleChange} placeholder="Asunto"></input>
                </div>
                <div>
                  <textarea
                    name="message"
                    id="message"
                    rows={7}
                    maxLength={130}
                    placeholder="Mensaje"
                    value={values.message}
                    onChange={handleChange}
                  ></textarea>
                  <p>{values.message.length}/130</p>
                </div>
                <button className="link" to={"/register"}>
                  Enviar
                </button>
              </form>
            )}
          </Formik>
        </Animated>
      </div>
    </>
  );
};

export default ContactPage;
