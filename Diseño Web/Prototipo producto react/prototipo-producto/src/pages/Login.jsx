import React, {useEffect} from "react";
import { Formik } from "formik";
import { Link } from "react-router-dom";
import Imagen from "./../img/Obreros.jpg";
import { Animated } from "react-animated-css";

const Login = () => {
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
              email: "ingrese email",
              password: "",
            }}
            handleSubmit={(valores) => {
              console.log("se envió");
              console.log(valores);
            }}
          >
            {({ values, handleChange, handleSubmit }) => (
              <form className="form" onSubmit={handleSubmit}>
                <h1>Bienvenido, por favor ingresa tus datos</h1>
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
                <button type="submit">Ingresar</button>
                <br />
                <Link className="link" to={"/register"}>
                  Registrarse
                </Link>
              </form>
            )}
          </Formik>
        </Animated>
      </div>
    </>
  );
};

export default Login;
