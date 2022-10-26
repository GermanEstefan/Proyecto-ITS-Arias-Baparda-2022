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
import NoPhoto from "../../assets/img/no-photo.png";

const Login = () => {

  const { setUserData } = useContext(userStatusContext);
  const navigate = useNavigate();

  const [values, handleValuesChange] = useForm({ email: "", password: "" });
  const [errorStatusForm, setErrorStatusForm] = useState({ email: true, password: true });

  useEffect(() => {
    window.scroll(0, 0);
  }, []);

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (Object.values(errorStatusForm).includes(true)) return;
    try {
      const resp = await fetchApi("auth-customers.php?url=login", "POST", values)
      if (resp.status === 'error') {
        return Swal.fire({
          icon: "error",
          text: resp.result.error_msg,
          timer: 3000,
          showConfirmButton: true,
        });
      }
      if (resp.status === 'successfully') {
        setUserData({
          name: resp.result.data.name,
          surname: resp.result.data.surname,
          email: resp.result.data.email,
          phone: resp.result.data.phone,
          address: resp.result.data.address,
          auth: true
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
      alert('ERROR, comunicarse con el administrador')
    }
  }

  return (
    <ContainerBase>
      <main className="login-page main-client">
        <div className="form-container">
          <img className={"form-img"} src={Imagen ? Imagen : NoPhoto} alt="Imagen"></img>
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
    </ContainerBase>
  );
};

export default Login;
