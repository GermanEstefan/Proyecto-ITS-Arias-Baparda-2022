/** @format */

import React, { useContext, useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import { Link } from "react-router-dom";
import Imagen from "../../assets/img/Obreros.jpg";
import { isEmail, isValidPassword } from "../../helpers/validateForms";
import Swal from "sweetalert2";
import { userStatusContext } from "../../App";
import { useForm } from "../../hooks/useForm";
import Input from "../../components/store/Input";
import { fetchApi } from "../../API/api";
import ContainerBase from "../../components/store/ContainerBase";

const Login = () => {
  const { setUserData } = useContext(userStatusContext);
  const navigate = useNavigate();

  const [values, handleValuesChange] = useForm({ email: "", password: "" });
  const [errorStatusForm, setErrorStatusForm] = useState({ email: true, password: true });

  useEffect(() => {
    window.scroll(0, 0);
  }, []);

  const handleSubmit = async (e) => {
    console.log(errorStatusForm);
    e.preventDefault();
    if (errorStatusForm.email)
      return Swal.fire({
        icon: "error",
        text: "Formato de correo incorrecto",
        timer: 3000,
        confirmButtonColor: "#f5990ff3",
        showConfirmButton: true,
      });
    try {
      const resp = await fetchApi("auth-customers.php?url=login", "POST", values);
      if (resp.status === "error") {
        return Swal.fire({
          icon: "error",
          text: resp.result.error_msg,
          
          showConfirmButton: true,
          confirmButtonColor: "#f5990ff3",
        });
      }
      if (resp.status === "successfully") {
        setUserData({
          name: resp.result.data.name,
          surname: resp.result.data.surname,
          email: resp.result.data.email,
          phone: resp.result.data.phone,
          address: resp.result.data.address,
          auth: true,
        });
        localStorage.setItem("token", resp.result.data.token);
        Swal.fire({
          icon: "success",
          text: "Ingreso exitoso",
          timer: 2000,
          showConfirmButton: false,
        });
        setTimeout(() => navigate("/"), 2000);
      }
    } catch (error) {
      console.error(error);
      alert("ERROR, comunicarse con el administrador");
    }
  };

  return (
    <ContainerBase>
      <div className="form-container">
        <img src={Imagen} alt="Imagen" />
        <form onSubmit={handleSubmit} autoComplete="off">
          <h1>Bienvenido, por favor ingresa tus datos</h1>
          <div className="inputSection">
            <Input
              name="email"
              id="email"
              value={values.email}
              placeholder="Email"
              onChange={handleValuesChange}
              setErrorStatusForm={setErrorStatusForm}
              validateFunction={isEmail}
            />
          </div>
          <div className="inputSection">
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
          </div>
          <button
            className="submit-button"
            disabled={values.password === "" || values.email === ""}
            type="submit"
          >
            Ingresar
          </button>
          <br />
          <Link className="link" to={"/register"}>
            Registrarse
          </Link>
        </form>
      </div>
    </ContainerBase>
  );
};

export default Login;
