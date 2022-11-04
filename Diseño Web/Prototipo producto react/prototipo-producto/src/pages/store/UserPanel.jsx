/** @format */

import React, { useState } from "react";
import { useNavigate } from "react-router-dom";
import ContainerBase from "../../components/store/ContainerBase";
import SalesHistory from "../../components/store/SalesHistory";
import Swal from "sweetalert2";
import UpdateAccountForm from "../../components/store/UpdateAccountForm";
import UpdatePasswordForm from "../../components/store/UpdatePasswordForm";

const UserPanel = () => {
  const [view, setView] = useState("personalInformation");
  const navigate = useNavigate();

  const handleLogout = () => {
    Swal.fire({
      icon: "warning",
      text: "¿Desea cerrar sesión?",

      showConfirmButton: true,
      showCancelButton: true,
      confirmButtonColor: "#f5990ff3",
      confirmButtonText: 'Si',
      cancelButtonText: 'No'
    }).then((result) => {
      console.log(result);
      if (result.isConfirmed) {
        localStorage.removeItem("token");
        navigate("/");
        window.location.reload();
      }
    });
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
            <li
              className={view === "personalInformation" && "isOrange"}
              onClick={() => handleChangeView("personalInformation")}
            >
              Datos personales
            </li>
            <li
              className={view === "changePassword" && "isOrange"}
              onClick={() => handleChangeView("changePassword")}
            >
              Cambiar contraseña
            </li>
            <li
              className={view === "buyHistory" && "isOrange"}
              onClick={() => handleChangeView("buyHistory")}
            >
              Historial de compras
            </li>
            <li
              className={view === "disabledAccount" && "isOrange"}
              onClick={() => handleChangeView("disabledAccount")}
            >
              Desactivar cuenta
            </li>
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
