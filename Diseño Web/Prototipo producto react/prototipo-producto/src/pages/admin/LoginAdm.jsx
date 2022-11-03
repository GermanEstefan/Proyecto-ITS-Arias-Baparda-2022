import React, { useContext } from "react";
import { useNavigate } from "react-router-dom";
import Swal from "sweetalert2";
import { fetchApi } from "../../API/api";
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
            const resp = await fetchApi("auth-employees.php?url=login", 'POST', values)
            console.log(resp)
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
                    rol: resp.result.data.rol,
                    ci : resp.result.data.ci,
                    auth: true
                });
                localStorage.setItem("token", resp.result.data.token);
                Swal.fire({
                    icon: "success",
                    text: "Ingreso exitoso",
                    timer: 2000,
                    showConfirmButton: false,
                });
                resetForm();
                setTimeout(() => {
                    navigate('/admin');
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