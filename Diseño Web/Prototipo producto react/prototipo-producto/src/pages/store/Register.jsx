/** @format */

import React, { useEffect, useState, useContext } from "react";
import { Link } from "react-router-dom";
import Imagen from "../../assets/img/Obreros.jpg";
import { useForm } from "../../hooks/useForm";
import Swal from "sweetalert2";
import { userStatusContext } from "../../App";
import { useNavigate } from "react-router-dom";
import { isEmail, isEmpty, isValidPassword } from "../../helpers/validateForms";
import Input from "../../components/store/Input";
import { fetchApi } from "../../API/api";
import ContainerBase from "../../components/store/ContainerBase";
import { Animated } from "react-animated-css";
import NoPhoto from "../../assets/img/no-photo.png";

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
    nameCurrent: true,
    surname: true,
    password: true,
  });

  const handleSubmit = async (e) => {
    e.preventDefault();
    console.log(errorStatusForm);
    if (Object.values(errorStatusForm).includes(true)) {
      return Swal.fire({
        icon: "error",
        text: "Formulario incompleto",
        timer: 3000,
        showConfirmButton: true,
      });
    }
    try {
      const resp = await fetchApi("auth-customers.php?url=register", "POST", values);
      if (resp.status === "error") {
        return Swal.fire({
          icon: "error",
          text: resp.result.error_msg,
          timer: 3000,
          showConfirmButton: true,
        });
      }
      if (resp.status === "successfully") {
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
          auth: true,
        });
        localStorage.setItem("token", resp.result.data.token);
        setTimeout(() => navigate("/"), 2000);
      }
    } catch (error) {
      console.log(error);
      alert("Error, informe al administrador");
    }
  };

  return (
    <ContainerBase>
      <div className="form-container">
        <img src={Imagen ? Imagen : NoPhoto} alt="Imagen" />
        <form onSubmit={handleSubmit} autoComplete="off">
          <h1>Registrate para comenzar tu experiencia</h1>
          {/* Solucionar bug de campo 'name' dando error en errorStatusForm. */}
          <div className="inputSection">
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
          </div>
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
          </div>
          <label>
            <Input
              name="type"
              id="type"
              type={"checkbox"}
              value={values.type === "COMPANY"}
              onChange={handleValuesChange}
              setErrorStatusForm={setErrorStatusForm}
            />
            Empresa
          </label>
          {values.type === "COMPANY" && (
            <Animated
              animationIn="fadeInDown"
              animationOut="fadeOutUp"
              animationInDuration={500}
              isVisible={true}
            >
              <div className="inputSection">
                <Input
                  name="nRut"
                  id="nRut"
                  value={values.nRut}
                  placeholder="nRut"
                  onChange={handleValuesChange}
                  setErrorStatusForm={setErrorStatusForm}
                  validateFunction={isEmpty}
                />
                <Input
                  name="company"
                  id="company"
                  value={values.company}
                  placeholder="Empresa"
                  onChange={handleValuesChange}
                  setErrorStatusForm={setErrorStatusForm}
                  validateFunction={isEmpty}
                />
              </div>
            </Animated>
          )}
          <button className="submit-button" type="submit">
            Registrarse
          </button>
          <br />
          <Link className="link" to={"/login"}>
            Ingresar
          </Link>
        </form>
      </div>
    </ContainerBase>
  );
};

export default Register;
