import React, { useEffect } from "react";
import { Formik } from "formik";
import { Link } from "react-router-dom";
import Imagen from "./../img/Obreros.jpg";
import { Animated } from "react-animated-css";

const Register = () => {
  useEffect(() => {
    window.scroll(0, 0);
  }, []);
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
              cpassword: "",
            }}
            handleSubmit={(valores) => {
              console.log("se envió");
              console.log(valores);
            }}
          >
            {({ values, handleChange, handleSubmit }) => (
              <form className="form" onSubmit={handleSubmit}>
                <h1>Registrate para comenzar tu experiencia</h1>
                <div>
                  <input
                    name="name"
                    id="name"
                    value={values.name}
                    placeholder="Nombre"
                    onChange={handleChange}
                  ></input>
                </div>
                <div>
                  <input
                    name="surname"
                    id="surname"
                    value={values.surname}
                    placeholder="Apellido"
                    onChange={handleChange}
                  ></input>
                </div>
                <div>
                  <input
                    name="email"
                    id="email"
                    value={values.email}
                    placeholder="Email"
                    onChange={handleChange}
                  ></input>
                </div>
                <div>
                  <input
                    name="password"
                    id="password"
                    value={values.password}
                    placeholder="Contraseña"
                    onChange={handleChange}
                  ></input>
                </div>
                <div>
                  <input
                    name="cpassword"
                    id="cpassword"
                    value={values.cpassword}
                    placeholder="Confirmar contraseña"
                    onChange={handleChange}
                  ></input>
                </div>
                <button>Registrarse</button>
                <br />
                <Link className="link" to={"/login"}>
                  Ingresar
                </Link>
              </form>
            )}
          </Formik>
        </Animated>
      </div>
    </>
  );
};

export default Register;
