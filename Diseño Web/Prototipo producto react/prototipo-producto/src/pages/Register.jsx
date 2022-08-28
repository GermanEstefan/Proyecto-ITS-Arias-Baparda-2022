import React, { useEffect, useState } from "react";

import { Link } from "react-router-dom";
import Imagen from "./../img/Obreros.jpg";
import { Animated } from "react-animated-css";
import { useForm } from "../hooks/useForm";
import { URL } from "../API/URL";
import Swal from "sweetalert2";
import { useNavigate } from "react-router-dom";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faCircleExclamation } from "@fortawesome/free-solid-svg-icons";
const Register = () => {
  const navigate = useNavigate();
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

  const [formErrors, setFormErrors] = useState({});

  const [values, handleValuesChange] = useForm(initialValues);

  const validate = (valuesParam) => {
    const errors = {};
    if (!valuesParam.name) errors.name = "El nombre es requerido";
    if (!valuesParam.surname) errors.surname = "El apellido es requerido";
    if (!valuesParam.email) errors.email = "El mail es requerido";
    if (!valuesParam.password) errors.password = "La contraseña es requerido";
    setFormErrors(errors);
    return errors;
  };

  useEffect(() => {}, [values]);

  const handleSubmit = (e) => {
    e.preventDefault();
    validate(values);
    const endpoint = URL + "auth-customers.php?url=register";
    fetch(endpoint, {
      method: "POST",
      body: JSON.stringify(values),
    })
      .then((resp) => resp.json())
      .then((respToJson) => {
        console.log(respToJson);
        localStorage.setItem("token", "");
        Swal.fire({
          icon: "success",
          text: "Te registraste exitosamente",
          timer: 1000,
          showConfirmButton: false,
        });
        setTimeout(() => {
          navigate("/");
        }, 1000);
      })
      .catch((error) => {
        console.error(error);
      });
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
          <div className="form">
            <h1>Registrate para comenzar tu experiencia</h1>
            <form onSubmit={handleSubmit}>
              <div>
                <input
                  name="name"
                  id="name"
                  value={values.name}
                  placeholder="Nombre"
                  onChange={handleValuesChange}
                ></input>
                {formErrors.name && (
                  <p className="formAlert">
                    {formErrors.name}{" "}
                    <FontAwesomeIcon icon={faCircleExclamation} />
                  </p>
                )}
              </div>
              <div>
                <input
                  name="surname"
                  id="surname"
                  value={values.surname}
                  placeholder="Apellido"
                  onChange={handleValuesChange}
                ></input>
                {formErrors.surname && (
                  <p className="formAlert">
                    {formErrors.surname}{" "}
                    <FontAwesomeIcon icon={faCircleExclamation} />
                  </p>
                )}
              </div>
              <div>
                <input
                  name="email"
                  id="email"
                  value={values.email}
                  placeholder="Email"
                  onChange={handleValuesChange}
                ></input>
                {formErrors.email && (
                  <p className="formAlert">
                    {formErrors.email}{" "}
                    <FontAwesomeIcon icon={faCircleExclamation} />
                  </p>
                )}
              </div>
              <div>
                <input
                  name="password"
                  id="password"
                  type={"password"}
                  value={values.password}
                  placeholder="Contraseña"
                  onChange={handleValuesChange}
                ></input>
                {formErrors.password && (
                  <p className="formAlert">
                    {formErrors.password}{" "}
                    <FontAwesomeIcon icon={faCircleExclamation} />
                  </p>
                )}
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
              <button className="submitButton" type="submit">
                Registrarse
              </button>
              <br />
              <Link className="link" to={"/login"}>
                Ingresar
              </Link>
            </form>
          </div>
        </Animated>
      </div>
    </>
  );
};

export default Register;
