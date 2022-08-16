import React, {useEffect} from "react";
import { Formik } from "formik";
import { Link } from "react-router-dom";
import Imagen from "./../img/Obreros.jpg";
import { Animated } from "react-animated-css";

const Register = () => {
  useEffect(() => {
    window.scroll(0, 0)
  }, [])
  return (
    <>
      <div className="form-container">
        <img className={"form-img"} src={Imagen}></img>
        <Animated
          animationIn="slideInRight"
          animationOut="fadeOut"
          animationInDuration="500"
          isVisible={true}
        >
        <Formik
          handleChange={(valores) => {
            console.log(valores);
          }}
          initialValues={{
            name: "",
            surname: "",
            email: "",
            password: "",
          }}
          handleSubmit={(valores) => {
            console.log("se envió");
            console.log(valores);
          }}
        >
          <form className="form">
            <h1>Registrate para comenzar tu experiencia</h1>
            <div>
              <input name="name" id="name" placeholder="Nombre"></input>
            </div>
            <div>
              <input name="surname" id="surname" placeholder="Apellido"></input>
            </div>
            <div>
              <input name="email" id="email" placeholder="Email"></input>
            </div>
            <div>
              <input name="password" id="password" placeholder="Contraseña"></input>
            </div>
            <div>
              <input placeholder="Confirmar contraseña"></input>
            </div>
            <button>Registrarse</button>
            <br />
            <Link className="link" to={"/login"}>
              Ingresar
            </Link>
          </form>
        </Formik>
        </Animated>
      </div>
    </>
  );
};

export default Register;
