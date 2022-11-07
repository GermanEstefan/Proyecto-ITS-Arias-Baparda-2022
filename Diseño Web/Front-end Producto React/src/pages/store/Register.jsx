/** @format */

import React, { useEffect, useState, useContext } from "react";
import { Link } from "react-router-dom";
import Imagen from "../../assets/img/Obreros.jpg";

import Swal from "sweetalert2";
import { userStatusContext } from "../../App";
import { useNavigate } from "react-router-dom";

import { fetchApi } from "../../API/api";
import ContainerBase from "../../components/store/ContainerBase";
import { Animated } from "react-animated-css";

import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faEye, faEyeSlash } from "@fortawesome/free-solid-svg-icons";
const Register = () => {
  const { setUserData } = useContext(userStatusContext);
  const navigate = useNavigate();
  const [viewPassword, setViewPassword] = useState(true);
  useEffect(() => {
    window.scroll(0, 0);
  }, []);

  const initialValues = {
    email: "",
    name: "",
    surname: "",
    password: "",
    type: "NORMAL",
    nRut: "",
    company: "",
  };
  const errorsInitialValues = {
    email: { error: false, message: "" },
    name: { error: false, message: "" },
    surname: { error: false, message: "" },
    password: { error: false, message: "" },
    nRut: { error: false, message: "" },
    company: { error: false, message: "" },
  };
  const [errors, setErrors] = useState(errorsInitialValues);
  const [values, setValues] = useState(initialValues);
  const [isEnterprise, setIsEnterprise] = useState(false);

  const handleSetValues = ({ target }) => {
    if (target.value !== "") {
      setErrors({ ...errors, [target.name]: { error: false, message: "" } });
    }
    if (isEnterprise) {
      if (target.value === "") {
        setErrors({ ...errors, [target.name]: { error: true, message: "Campo requerido" } });
      }
    }
    if (!isEnterprise) {
      if (target.name !== "nRut" && target.name !== "company")
        if (target.value === "") {
          setErrors({ ...errors, [target.name]: { error: true, message: "Campo requerido" } });
        }
    }
    if (target.name === "password") {
      if (target.value.length < 6) {
        setErrors({ ...errors, [target.name]: { error: true, message: "Contraseña muy corta" } });
      }
    }

    setValues({ ...values, [target.name]: target.value });
  };

  const handleCheckbox = ({ target }) => {
    setIsEnterprise(target.checked);
    if (target.checked) {
      setValues({ ...values, type: "COMPANY" });
    }
    if (!target.checked) {
      setValues({ ...values, type: "NORMAL" });
    }
  };
  const handleSubmit = async (e) => {
    e.preventDefault();
    console.log(values);
    try {
      const resp = await fetchApi("auth-customers.php?url=register", "POST", values);
      if (resp.status === "error") {
        return Swal.fire({
          icon: "error",
          text: resp.result.error_msg,
          timer: 3000,
          showConfirmButton: true,
          confirmButtonColor: "#f5990ff3",
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
        <img src={Imagen} alt="Imagen" />
        <form onSubmit={handleSubmit} autoComplete="off">
          <h1>Registrate para comenzar tu experiencia</h1>
          {/* Solucionar bug de campo 'name' dando error en errorStatusForm. */}
          <div className="inputSection">
            <div>
              <input
                name="name"
                id="name"
                style={errors.name.error ? { borderColor: "red" } : {}}
                value={values.name}
                type={"text"}
                placeholder="Nombre"
                onChange={(e) => handleSetValues(e)}
                onBlur={(e) => handleSetValues(e)}
              />

              {errors.name.error && <span className="spanError">{errors.name.message}</span>}
            </div>
            <div>
              <input
                name="surname"
                id="surname"
                type={"text"}
                style={errors.surname.error ? { borderColor: "red" } : {}}
                value={values.surname}
                placeholder="Apellido"
                onChange={(e) => handleSetValues(e)}
                onBlur={(e) => handleSetValues(e)}
              />
              {errors.surname.error && <span className="spanError">{errors.surname.message}</span>}
            </div>
          </div>
          <div className="inputSection">
            <div>
              <input
                name="email"
                id="email"
                type={"email"}
                style={errors.email.error ? { borderColor: "red" } : {}}
                value={values.email}
                placeholder="Email"
                onChange={(e) => handleSetValues(e)}
                onBlur={(e) => handleSetValues(e)}
              />
              {errors.email.error && <span className="spanError">{errors.email.message}</span>}
            </div>
            <div>
              <div style={{ display: "flex", justifyContent: "flex-end" }}>
                <input
                  name="password"
                  id="password"
                  type={viewPassword ? "password" : "text"}
                  value={values.password}
                  style={errors.password.error ? { borderColor: "red" } : {}}
                  placeholder="Contraseña"
                  onChange={(e) => handleSetValues(e)}
                  onBlur={(e) => handleSetValues(e)}
                />

                <FontAwesomeIcon
                  style={{
                    margin: "13px 18px 13px -18px",
                    position: "absolute",
                    cursor: "pointer",
                  }}
                  onClick={() => setViewPassword(!viewPassword)}
                  icon={!viewPassword ? faEye : faEyeSlash}
                  color="gray"
                />
              </div>
              {errors.password.error && (
                <span className="spanError">{errors.password.message}</span>
              )}
            </div>
          </div>
          <label>
            <input
              name="type"
              id="type"
              type={"checkbox"}
              value={values.type === "COMPANY"}
              onChange={(e) => handleCheckbox(e)}
              onBlur={(e) => handleCheckbox(e)}
            />
            Empresa
          </label>
          {isEnterprise && (
            <Animated
              animationIn="fadeInDown"
              animationOut="fadeOutUp"
              animationInDuration={500}
              isVisible={true}
            >
              <div className="inputSection">
                <div>
                  <input
                    name="nRut"
                    id="nRut"
                    type={"text"}
                    style={errors.nRut.error ? { borderColor: "red" } : {}}
                    value={values.nRut}
                    placeholder="nRut"
                    onChange={(e) => handleSetValues(e)}
                    onBlur={(e) => handleSetValues(e)}
                  />
                  {errors.nRut.error && <span className="spanError">{errors.nRut.message}</span>}
                </div>
                <div>
                  <input
                    name="company"
                    id="company"
                    type={"text"}
                    style={errors.company.error ? { borderColor: "red" } : {}}
                    value={values.company}
                    placeholder="Empresa"
                    onChange={(e) => handleSetValues(e)}
                    onBlur={(e) => handleSetValues(e)}
                  />
                  {errors.company.error && (
                    <span className="spanError">{errors.company.message}</span>
                  )}
                </div>
              </div>
            </Animated>
          )}
          <button className="submit-button" type="submit">
            Registrarse
          </button>
          
          <Link className="link" to={"/login"}>
            Ingresar
          </Link>
        </form>
      </div>
    </ContainerBase>
  );
};

export default Register;
