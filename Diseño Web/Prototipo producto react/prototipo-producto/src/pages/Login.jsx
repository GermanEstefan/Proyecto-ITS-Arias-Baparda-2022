import React from "react";
import { Formik } from "formik";
import { Link } from "react-router-dom";
import Imagen from "./../img/Obreros.jpg";

const Login = () => {
  return (
    <>
      <div className="form-container">
        <img src={Imagen} alt='' width="650px"></img>
        <Formik
        initialValues={
            {
                email: '',
                password: ''
            }
        }
          onSubmit={(valores) => {
            console.log(valores);
          }}
          handleChange={
              (e) => {
                  console.log(e)
              }
          }
        >
          {({ values, handleChange, handleSubmit }) => (
            <form className="form" onSubmit={handleSubmit}>
              <h1>Bienvenido, por favor ingresa tus datos</h1>
              <div>
                <input
                  onChange={handleChange}
                  type="text"
                  placeholder="Email"
                  id="email"
                  name="email"
                  value={values.email}
                ></input>
              </div>
              <div>
                <input
                  onChange={handleChange}
                  type={"password"}
                  placeholder="Contraseña"
                  id="password"
                  name="password"
                  value={values.password}
                ></input>
              </div>
              <button >Ingresar</button>
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
