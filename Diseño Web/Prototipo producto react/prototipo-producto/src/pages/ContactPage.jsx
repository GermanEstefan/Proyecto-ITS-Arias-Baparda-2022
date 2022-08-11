import React from "react";
import { Formik } from "formik";
import Imagen from './../img/Obreros.jpg'

const ContactPage = () => {
  return (
    <>
    <div className='form-container'>
        <img src={Imagen} width="650px"></img>
      <Formik>
        <form className="form">
          <h1>Envianos tu mensaje</h1>
          <div>
            <input placeholder="Asunto"></input>
          </div>
          <div>
            <input placeholder="Mensaje"></input>
          </div>
          <button className="link" to={"/register"}>
            Enviar
          </button>
        </form>
      </Formik>
      </div>
    </>
  );
};

export default ContactPage;
