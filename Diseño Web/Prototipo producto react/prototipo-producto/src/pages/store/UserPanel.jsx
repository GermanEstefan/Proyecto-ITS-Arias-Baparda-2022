import React, { useState } from "react";
import UpdateAccountForm from "../../components/store/UpdateAccountForm";

const UserPanel = () => {
    
    const [view, setView] = useState('personalInformation');

    const handleChangeView = (view) => {
        setView(view)
    }
    
    return(
        <main className="user-panel-page">
            <aside>
                <h1>Configuracion</h1>
                <ul>
                    <li onClick={() => handleChangeView('personalInformation')} >Datos personales</li>
                    <li onClick={() => handleChangeView('changePassword')} >Cambiar contraseña</li>
                    <li onClick={() => handleChangeView('buyHistory')} >Historial de compras</li>
                    <li onClick={() => handleChangeView('payMethods')}>Metodos de pago</li>
                    <li onClick={() => handleChangeView('disabledAccount')}>Desactivar cuenta</li>
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
                    <h1>Cambiar contraseña</h1>
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
    )
}

export default UserPanel;