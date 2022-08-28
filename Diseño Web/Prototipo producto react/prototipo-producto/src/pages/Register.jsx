import React, { useEffect } from "react";

import { Link } from "react-router-dom";
import Imagen from "./../img/Obreros.jpg";
import { Animated } from "react-animated-css";
import { useForm } from "../hooks/useForm";
import { URL } from "../API/URL";
import Swal from 'sweetalert2'
import { useNavigate } from "react-router-dom";
const Register = () => {
  const navigate = useNavigate()
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
    console.log(JSON.stringify(values))
    const endpoint = URL + "auth-customers.php?url=register";
    fetch(endpoint, {
      method: "POST",
      body: JSON.stringify(values),
    })
      .then((resp) => resp.json())
      .then((respToJson) => {
        console.log(respToJson)
        
        Swal.fire({
          icon: 'success',
          text: 'Te registraste exitosamente',
          timer: 1000,
          showConfirmButton: false,
        })
        setTimeout(() => {
          navigate('/')
        }, 1000);
      }).catch((error) => {
        console.error(error)
      })
      resetForm()
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
                type={'password'}
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
            <button className="submitButton" type="submit">Registrarse</button>
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
