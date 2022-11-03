/** @format */

import React, { useState, useEffect } from "react";
import { isEmpty } from "../../helpers/validateForms";
import { useForm } from "../../hooks/useForm";
import Swal from "sweetalert2";
import Input from "./Input";

import { Animated } from "react-animated-css";
import { fetchApi } from "../../API/api";

const UpdatePasswordForm = () => {
  const [values, handleValuesChange] = useForm({
    oldPassword: "",
    newPassword: "",
    newPassword2: "",
  });
  const [doPasswordsMatch, setDoPasswordsMatch] = useState(true);

  const [errorStatusForm, setErrorStatusForm] = useState({
    oldPassword: false,
    newPassword: false,
    newPassword2: false,
  });
  useEffect(() => {
    window.scroll(0, 0);
  }, []);

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (Object.values(errorStatusForm).includes(true)) return;
    if (values.newPassword !== values.newPassword2)
      return Swal.fire({
        icon: "error",
        text: "Las contraseñas no coinciden",
        showConfirmButton: true,
      });
    if (values.newPassword == values.oldPassword)
      return Swal.fire({
        icon: "error",
        text: "La contraseña nueva es igual a la antigua",
        showConfirmButton: true,
      });
    try {
      const resp = await fetchApi("auth-customers.php?url=updatePassword", "PUT", {
        oldPassword: values.oldPassword,
        newPassword: values.newPassword,
      });
      if (resp.status === "successfully") {
        Swal.fire({
          icon: "success",
          text: "Contraseña actualizada correctamente",
          timer: 2000,
          showConfirmButton: false,
        });
      } else {
        return Swal.fire({
          icon: "error",
          text: resp.result.error_msg,
          timer: 3000,
          showConfirmButton: true,
        });
      }
    } catch (error) {
      return Swal.fire({
        icon: "error",
        text: "Error 500, servidor caido",
        timer: 3000,
        showConfirmButton: true,
      });
    }
  };

  return (
    <Animated
      animationIn="fadeIn"
      animationOut="fadeOutRight"
      animationInDuration={500}
      isVisible={true}
    >
      <form onSubmit={handleSubmit}>
        <h2>Cambiar contraseña</h2>

        <div>
          <label>Contraseña actual: </label>
          <Input
            type="password"
            onChange={handleValuesChange}
            validateFunction={isEmpty}
            value={values.oldPassword}
            name="oldPassword"
            setErrorStatusForm={setErrorStatusForm}
          />
        </div>

        <div>
          <label>Nueva contraseña: </label>
          <Input
            type="password"
            onChange={handleValuesChange}
            validateFunction={isEmpty}
            value={values.newPassword}
            setErrorStatusForm={setErrorStatusForm}
            name="newPassword"
          />
        </div>

        <div>
          <label>Confirmar contraseña: </label>
          <input
            type="password"
            onChange={handleValuesChange}
            value={values.newPassword2}
            name="newPassword2"
          />
        </div>

        <button className="submit-button">Modificar</button>
      </form>
    </Animated>
  );
};

export default UpdatePasswordForm;
