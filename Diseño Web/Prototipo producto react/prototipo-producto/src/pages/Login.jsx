import React, { useContext, useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import { Link } from "react-router-dom";
import Imagen from "./../img/Obreros.jpg";
import { Animated } from "react-animated-css";
import { useForm } from "../hooks/useForm";
import { URL } from "../API/URL";
import { userStatusContext } from "../App";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faCircleExclamation } from "@fortawesome/free-solid-svg-icons";

const Login = () => {
  const [isMounted, setIsMounted] = useState(true);
  const { userData, setUserData } = useContext(userStatusContext);
  const [formErrors, setFormErrors] = useState({});
  const navigate = useNavigate();

  useEffect(() => {
    window.scroll(0, 0);
  }, []);

  useEffect(() => {
    return () => {
      setIsMounted(false);
    };
  }, [userData]);

  const initialValues = {
    email: "",
    password: "",
  };
  const [values, handleValuesChange, resetForm] = useForm(initialValues);
  const validate = (valuesParam) => {
    const errors = {};
    if (!valuesParam.name) errors.name = "El nombre es requerido";
    if (!valuesParam.password) errors.password = "La contraseña es requerido";
    setFormErrors(errors);
    return errors;
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    validate(values);
    const endpoint = URL + "auth-customers.php?url=login";
    fetch(endpoint, {
      method: "POST",
      body: JSON.stringify(values),
    })
      .then((resp) => resp.json())
      .then((respToJson) => {
        console.log(respToJson);
        localStorage.setItem("token", respToJson.result.data.token);
        if (isMounted) {
          setUserData(respToJson.result.data);
          navigate("/");
          resetForm();
        }
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
          <form className="form" onSubmit={handleSubmit}>
            <h1>Bienvenido, por favor ingresa tus datos</h1>
            <div>
              <input
                name="email"
                id="email"
                value={values.email}
                placeholder="Email"
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
                name="password"
                id="password"
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
            <button className="submitButton" type="submit">
              Ingresar
            </button>
            <br />
            <Link className="link" to={"/register"}>
              Registrarse
            </Link>
          </form>
        </Animated>
      </div>
    </>
  );
};

export default Login;
