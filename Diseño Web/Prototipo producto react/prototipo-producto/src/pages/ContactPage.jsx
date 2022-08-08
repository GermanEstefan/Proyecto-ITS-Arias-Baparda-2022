import React from "react";
import Navbar from "../components/Navbar";
import { Formik } from "formik";

const ContactPage = () => {
  console.log("contact page");
  return (
    <>
      <Navbar />
      <div className="form-container">
        <img src={'Imagen'} width="650px" alt=""></img>
        <Formik>
          <form className="form">
            <h1>Contactanos</h1>
            <div>
              <input type={'text'} placeholder="Asunto"></input>
            </div>
            <div>
              <input type={'text'} placeholder="Mensaje"></input>
            </div>
            <button>Enviar</button>
            <br />
          </form>
        </Formik>
      </div>
    </>
  );
};

export default ContactPage;
