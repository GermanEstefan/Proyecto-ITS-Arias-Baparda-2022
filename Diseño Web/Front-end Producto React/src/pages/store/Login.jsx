/** @format */

import React, { useContext, useState, useEffect } from "react";
import { useNavigate, Link } from "react-router-dom";
import Imagen from "../../assets/img/Obreros.jpg";
import { isEmail, isEmpty, isValidPassword } from "../../helpers/validateForms";
import Swal from "sweetalert2";
import { userStatusContext } from "../../App";
import { fetchApi } from "../../API/api";
import ContainerBase from "../../components/store/ContainerBase";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faEye, faEyeSlash } from "@fortawesome/free-solid-svg-icons";

const Login = () => {
  const { setUserData } = useContext(userStatusContext);
  const navigate = useNavigate();

  const initialValues = {
    email: "",
    password: "",
  };
  const [viewPassword, setViewPassword] = useState(true);
  const initialErrors = {
    email: { error: false, message: "" },
    password: { error: false, message: "" },
  };

  const [values, setValues] = useState(initialValues);
  const [errors, setErrors] = useState(initialErrors);
  const [isDissable, setIsDissable] = useState(true);

  useEffect(() => {
    window.scroll(0, 0);
  }, []);

  const handleValuesChange = ({ target }) => {
    if (target.value === "") {
      setErrors({ ...errors, [target.name]: isEmpty(target.value) });
    } else {
      if (target.name === "email") {
        setErrors({ ...errors, [target.name]: isEmail(target.value) });
      }
      if (target.name === "password") {
        setErrors({ ...errors, [target.name]: isValidPassword(target.value) });
      }
    }

    setValues({ ...values, [target.name]: target.value });

    // setIsDissable(
    //   Object.keys(errors).some(function(param) {
    //     return errors[param].error;
    //   })
    // );
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (errors.email.error) {
      return Swal.fire({
        icon: "error",
        text: "Formato de correo incorrecto",
        timer: 2000,
        confirmButtonColor: "#f5990ff3",
        showConfirmButton: true,
      });
    }
    if (errors.password.error) {
      return Swal.fire({
        icon: "error",
        text: "Contraseña invalida",
        timer: 2000,
        confirmButtonColor: "#f5990ff3",
        showConfirmButton: true,
      });
    }
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
            <input
              name="email"
              id="email"
              value={values.email}
              type="text"
              placeholder="Email"
              style={errors.email.error ? { borderColor: "red" } : {}}
              onChange={(e) => handleValuesChange(e)}
              onBlur={(e) => handleValuesChange(e)}
            />
          </div>
          {errors.email.error && <span className="spanError">{errors.email.message}</span>}
          <div className="inputSection">
            <div style={{ display: "flex", justifyContent: "flex-end" }}>
              <input
                name="password"
                id="password"
                type={viewPassword ? "password" : "text"}
                value={values.password}
                style={errors.password.error ? { borderColor: "red" } : {}}
                placeholder="Contraseña"
                onChange={(e) => handleValuesChange(e)}
                onBlur={(e) => handleValuesChange(e)}
              />

              <FontAwesomeIcon
                style={{ margin: "13px 18px 13px -18px", position: "absolute", cursor: "pointer" }}
                onClick={() => setViewPassword(!viewPassword)}
                icon={!viewPassword ? faEye : faEyeSlash}
                color="gray"
              />
            </div>
          </div>
          {errors.password.error && <span className="spanError">{errors.password.message}</span>}
          <button
            className="submit-button"
            // disabled={isDissable}
            type="submit"
          >
            Ingresar
          </button>

          <Link to="/register">Registrarse</Link>
        </form>
      </div>
    </ContainerBase>
  );
};

export default Login;
