import React from "react";
import { Formik } from "formik";
import { Link } from "react-router-dom";
import Imagen from "./../img/Obreros.jpg";

const Login = () => {
  return (
    <>
      <div className="form-container">
        <img className={'form-img'} src={Imagen} ></img>
        <Formik
          handleChange={(valores) => {
            console.log(valores);
          }}
          initialValues={{
            email: "ingrese email",
            password: "",
          }}
          handleSubmit={(valores) => {
              console.log('se envió')
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
      </div>
    </>
  );
};

export default Login;
