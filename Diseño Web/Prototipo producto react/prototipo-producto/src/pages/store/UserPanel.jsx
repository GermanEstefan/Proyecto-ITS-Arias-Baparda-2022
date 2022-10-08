import React, { useState } from "react";
import { useNavigate } from "react-router-dom";
import ContainerBase from "../../components/store/ContainerBase";
import UpdateAccountForm from "../../components/store/UpdateAccountForm";
import UpdatePasswordForm from "../../components/store/UpdatePasswordForm";

const UserPanel = () => {
    
    const [view, setView] = useState('personalInformation');
    const navigate = useNavigate()

    const handleLogout = () => {
        localStorage.removeItem('token');
        navigate('/');
        window.location.reload();
    }

    const handleChangeView = (view) => {
        setView(view)
    }
    
    return(
        <ContainerBase>
        <main className="user-panel-page main-client">
            <aside>
                <h1>Configuracion</h1>
                <ul>
                    <li onClick={() => handleChangeView('personalInformation')} >Datos personales</li>
                    <li onClick={() => handleChangeView('changePassword')} >Cambiar contraseña</li>
                    <li onClick={() => handleChangeView('buyHistory')} >Historial de compras</li>
                    <li onClick={() => handleChangeView('payMethods')}>Metodos de pago</li>
                    <li onClick={() => handleChangeView('disabledAccount')}>Desactivar cuenta</li>
                    <li onClick={handleLogout}>Cerrar sesion</li>
                </ul>
            </aside>
            <section>
                {
                    view === 'personalInformation'
                    ?
                    <UpdateAccountForm/>
                    :
                    view === 'changePassword'
                    ?
                    <UpdatePasswordForm/>
                    :
                    view === 'buyHistory'
                    ?
                    <h1>Historial de compras</h1>
                    :
                    view === 'payMethods'
                    ?
                    <h1>Metodos de pago</h1>
                    :
                    view === 'disabledAccount'
                    ?
                    <h1>Desactivar cuenta</h1>
                    :
                    null
                }
            </section>
        </main>
        </ContainerBase>
    )
}

export default UserPanel;