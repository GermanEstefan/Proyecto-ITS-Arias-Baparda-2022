import React, { useContext, useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import { Link } from "react-router-dom";
import Imagen from "./../img/Obreros.jpg";
import { Animated } from "react-animated-css";
import { useForm } from "../hooks/useForm";
import { URL } from "../API/URL";
import { userStatusContext } from "../App";
import Input from "../components/Input";
import { isEmail, isValidPassword } from "../helpers/validateForms";
import Swal from "sweetalert2";

const Login = () => {

  const [isMounted, setIsMounted] = useState(true);
  const { userData, setUserData } = useContext(userStatusContext);
  const navigate = useNavigate();

  useEffect(() => {
    window.scroll(0, 0);
  }, []);

  useEffect(() => {
    return () => {
      setIsMounted(false);
    };
  }, [userData]);

  const [values, handleValuesChange, resetForm] = useForm({ email: "", password: "" });
  const [errorStatusForm, setErrorStatusForm] = useState({ email: true, password: true });

  const handleSubmit = async (e) => {
    e.preventDefault();
    if(Object.values(errorStatusForm).includes(true)) return;
    try {
      const endpoint = URL + "auth-customers.php?url=login";
      const resp = await fetch(endpoint, { method: "POST", body: JSON.stringify(values) });
      const respToJson = await resp.json();
      if (respToJson.status === 'error') {
        return Swal.fire({
          icon: "error",
          text: respToJson.result.error_msg,
          timer: 3000,
          showConfirmButton: true,
        });
      } 
      if (respToJson.status === 'successfully') {
        if (isMounted) {
          setUserData(respToJson.result.data);
          localStorage.setItem("token", respToJson.result.data.token);
          navigate("/");
        }
      }
    } catch (error) {
      console.error(error);
      alert('ERROR, comunicarse con el administrador')
    }
  }

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
          <form className="form" onSubmit={handleSubmit} autoComplete="off">
            <h1>Bienvenido, por favor ingresa tus datos</h1>
            <Input
              name="email"
              id="email"
              value={values.email}
              placeholder="Email"
              onChange={handleValuesChange}
              setErrorStatusForm={setErrorStatusForm}
              validateFunction={isEmail}
            />
            <Input
              name="password"
              id="password"
              type="password"
              value={values.password}
              placeholder="Contraseña"
              onChange={handleValuesChange}
              setErrorStatusForm={setErrorStatusForm}
              validateFunction={isValidPassword}
            />
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
