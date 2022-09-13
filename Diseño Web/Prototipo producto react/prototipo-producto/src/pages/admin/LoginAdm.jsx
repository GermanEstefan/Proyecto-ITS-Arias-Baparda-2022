import React, { useContext } from "react";
import { useNavigate } from "react-router-dom";
import Swal from "sweetalert2";
import { URL } from "../../API/URL";
import { userStatusContext } from "../../App";
import LogoCliente from "../../assets/img/Cliente-nombre1.svg";
import { useForm } from "../../hooks/useForm";


const LoginAdm = () => {

    const { setUserData } = useContext(userStatusContext);
    const [values, handelChangeValues, resetForm] = useForm({ ci: '', password: '' })
    const navigate = useNavigate();

    const handleLogin = async (e) => {
        e.preventDefault();
        try {
            const resp = await fetch(URL + "auth-employees.php?url=login", {
                method: 'POST',
                body: JSON.stringify(values)
            });
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
                Swal.fire({
                    icon: "success",
                    text: "Ingreso exitoso",
                    timer: 2000,
                    showConfirmButton: false,
                });
                resetForm();
                setTimeout(() => {
                    navigate('/admin/dashboard');
                }, 1000)
                
            }

        } catch (error) {
            alert(error);
        }

    }

    return (
        <main className="login-page-adm">
            <form autoComplete="off" onSubmit={handleLogin}>
                <img src={LogoCliente} alt="logo" />
                <input
                    type="text"
                    placeholder="C.I"
                    value={values.ci}
                    name="ci"
                    onChange={handelChangeValues}
                />
                <input
                    type="password"
                    placeholder="Contraseña"
                    value={values.password}
                    name="password"
                    onChange={handelChangeValues}
                />
                <button>Login</button>
            </form>
        </main>
    )
}

export default LoginAdm;