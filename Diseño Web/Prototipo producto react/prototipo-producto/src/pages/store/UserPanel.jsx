/** @format */

import React, { useState } from "react";
import { useNavigate } from "react-router-dom";
import ContainerBase from "../../components/store/ContainerBase";
import SalesHistory from "../../components/store/SalesHistory";

import UpdateAccountForm from "../../components/store/UpdateAccountForm";
import UpdatePasswordForm from "../../components/store/UpdatePasswordForm";

const UserPanel = () => {
  const [view, setView] = useState("personalInformation");
  const navigate = useNavigate();

  const handleLogout = () => {
    localStorage.removeItem("token");
    navigate("/");
    window.location.reload();
  };

  const handleChangeView = (view) => {
    setView(view);
  };

  return (
    <ContainerBase>
      <main className="user-panel-page">
        <aside>
          <h1>Configuracion</h1>
          <ul>
            <li onClick={() => handleChangeView("personalInformation")}>Datos personales</li>
            <li onClick={() => handleChangeView("changePassword")}>Cambiar contraseña</li>
            <li onClick={() => handleChangeView("buyHistory")}>Historial de compras</li>
            <li onClick={() => handleChangeView("disabledAccount")}>Desactivar cuenta</li>
            <li className="danger" onClick={handleLogout}>
              Cerrar sesion
            </li>
          </ul>
        </aside>
        
          <section>
            {view === "personalInformation" ? (
              <UpdateAccountForm />
            ) : view === "changePassword" ? (
              <UpdatePasswordForm />
            ) : view === "buyHistory" ? (
              <SalesHistory />
            ) : view === "disabledAccount" ? (
              <h1>Desactivar cuenta</h1>
            ) : null}
          </section>
      </main>
    </ContainerBase>
  );
};

export default UserPanel;
