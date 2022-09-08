import React, { useEffect, useState, useContext } from "react";
import { Link } from "react-router-dom";
import Imagen from "../../assets/img/Obreros.jpg";
import { useForm } from "../../hooks/useForm";
import { URL } from "../../API/URL";
import Swal from "sweetalert2";
import { userStatusContext } from "../../App";
import { useNavigate } from "react-router-dom";
import { isEmail, isEmpty, isValidPassword } from "../../helpers/validateForms";
import Input from "../../components/store/Input";

const Register = () => {
  const { setUserData } = useContext(userStatusContext);
  const navigate = useNavigate();
  useEffect(() => {
    window.scroll(0, 0);
  }, []);

  const initialValues = {
    email: "",
    name: "",
    surname: "",
    password: "",
    type: "NORMAL",
  };

  const [values, handleValuesChange] = useForm(initialValues);
  const [errorStatusForm, setErrorStatusForm] = useState({
    email: true,
    name: true,
    surname: true,
    password: true,
  });

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (Object.values(errorStatusForm).includes(true)) {
      return Swal.fire({
        icon: "error",
        text: "Formulario incompleto",
        timer: 3000,
        showConfirmButton: true,
      });
    }
    try {
      const endpoint = URL + "auth-customers.php?url=register";
      const resp = await fetch(endpoint, {
        method: "POST",
        body: JSON.stringify(values),
      });
      const respToJson = await resp.json();
      if (respToJson.status === "error") {
        return Swal.fire({
          icon: "error",
          text: respToJson.result.error_msg,
          timer: 3000,
          showConfirmButton: true,
        });
      }
      console.log(respToJson)
      if (respToJson.status === "successfully") {
        Swal.fire({
          icon: "success",
          text: "Te registraste exitosamente",
          timer: 2000,
          showConfirmButton: false,
        });
        setUserData({
          name: values.name,
          surname: values.surname,
          email: values.email,
          address: null,
          phone: null,
          auth: true
        });
        localStorage.setItem("token", respToJson.result.data.token);
        setTimeout(() => {
          navigate("/");
        }, 2000);
      }
    } catch (error) {
      console.log(error);
      alert("Error, informe al administrador");
    }
  };

  return (
 
      <div className="form-container">
        <img className={"form-img"} src={Imagen} alt="Imagen"/>
            <form onSubmit={handleSubmit} autoComplete="off">
            <h1>Registrate para comenzar tu experiencia</h1>
              <Input
                name="name"
                id="name"
                value={values.name}
                placeholder="Nombre"
                onChange={handleValuesChange}
                setErrorStatusForm={setErrorStatusForm}
                validateFunction={isEmpty}
              />

              <Input
                name="surname"
                id="surname"
                value={values.surname}
                placeholder="Apellido"
                onChange={handleValuesChange}
                setErrorStatusForm={setErrorStatusForm}
                validateFunction={isEmpty}
              />

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
                type={"password"}
                value={values.password}
                placeholder="Contraseña"
                onChange={handleValuesChange}
                setErrorStatusForm={setErrorStatusForm}
                validateFunction={isValidPassword}
              />

              <button className="submit-button" type="submit">
                Registrarse
              </button>
              <br />
              <Link className="link" to={"/login"}>
                Ingresar
              </Link>
            </form>
      </div>
   
  );
};

export default Register;
