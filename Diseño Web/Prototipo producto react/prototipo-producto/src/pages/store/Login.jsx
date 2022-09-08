import React, { useContext, useState } from "react";
import { useNavigate } from "react-router-dom";
import { Link } from "react-router-dom";
import Imagen from "../../assets/img/Obreros.jpg";
import { isEmail, isValidPassword } from "../../helpers/validateForms";
import Swal from "sweetalert2";
import { URL } from "../../API/URL";
import { userStatusContext } from "../../App";
import { useForm } from "../../hooks/useForm";
import Input from "../../components/store/Input";

const Login = () => {

  const { setUserData } = useContext(userStatusContext);
  const navigate = useNavigate();

  const [values, handleValuesChange] = useForm({ email: "", password: "" });
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
      console.log(respToJson)
      if (respToJson.status === 'successfully') {
        setUserData({
          name: respToJson.result.data.name,
          surname: respToJson.result.data.surname,
          email: respToJson.result.data.email,
          phone: respToJson.result.data.phone,
          address: respToJson.result.data.address,
          auth: true
        });
        localStorage.setItem("token", respToJson.result.data.token);
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
      alert('ERROR, comunicarse con el administrador')
    }
  }

  return (
    <main className="login-page">
      <div className="form-container">
        <img className={"form-img"} src={Imagen} alt="Imagen"></img>
          <form onSubmit={handleSubmit} autoComplete="off">
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
            <button className="submit-button" type="submit">
              Ingresar
            </button>
            <br />
            <Link className="link" to={"/register"}>
              Registrarse
            </Link>
          </form>
      </div>
    </main>
  );
};

export default Login;
