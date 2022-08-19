import React, { useEffect } from "react";

import { Link } from "react-router-dom";
import Imagen from "./../img/Obreros.jpg";
import { Animated } from "react-animated-css";
import { useForm } from "../hooks/useForm";
import { URL } from "../API/URL";

const Register = () => {
  useEffect(() => {
    window.scroll(0, 0);
  }, []);

  const initialValues = {
    email: "",
    name: "",
    surname: "",
    phone: "",
    password: "",
    address: "",
    company: "",
    nRut: "",
  };
  const [values, handleValuesChange, resetForm] = useForm(initialValues);

  const handleSubmit = (e) => {
    e.preventDefault();
    const endpoint = URL + "auth-customers.php?url=register";
    console.log(values)
    fetch(endpoint, {
      method: "POST",
      body: JSON.stringify(values),
    })
      .then((resp) => resp.json())
      .then((respToJson) => console.log(respToJson));
  };

  return (
    <>
      <div className="form-container">
        <img className={"form-img"} src={Imagen} alt="Imagen"></img>
        <Animated
          animationIn="slideInRight"
          animationOut="fadeOut"
          animationInDuration={500}
          isVisible={true}
        >
          <form className="form" onSubmit={handleSubmit}>
            <h1>Registrate para comenzar tu experiencia</h1>
            <div>
              <input
                name="name"
                id="name"
                value={values.name}
                placeholder="Nombre"
                onChange={handleValuesChange}
              ></input>
            </div>
            <div>
              <input
                name="surname"
                id="surname"
                value={values.surname}
                placeholder="Apellido"
                onChange={handleValuesChange}
              ></input>
            </div>
            <div>
              <input
                name="email"
                id="email"
                value={values.email}
                placeholder="Email"
                onChange={handleValuesChange}
              ></input>
            </div>
            <div>
              <input
                name="password"
                id="password"
                value={values.password}
                placeholder="Contraseña"
                onChange={handleValuesChange}
              ></input>
            </div>
            {/* <div>
              <input
                name="cpassword"
                id="cpassword"
                value={password2}
                placeholder="Confirmar contraseña"
                onChange={(e) => setPassword2(e.target.value)}
              ></input>
            </div> */}
            <button>Registrarse</button>
            <br />
            <Link className="link" to={"/login"}>
              Ingresar
            </Link>
          </form>
        </Animated>
      </div>
    </>
  );
};

export default Register;
