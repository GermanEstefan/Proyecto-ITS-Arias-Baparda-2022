/** @format */

import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import React, { useState } from "react";
import { useContext } from "react";
import { Animated } from "react-animated-css";
import Swal from "sweetalert2";
import { useNavigate } from "react-router-dom";
import { fetchApi } from "../../API/api";
import { userStatusContext } from "../../App";
import { faExclamationCircle } from "@fortawesome/free-solid-svg-icons";

const DisableAccount = () => {
  const navigate = useNavigate();
  const { userData } = useContext(userStatusContext);
  const [thisPassword, setThisPassword] = useState("");
  const handleChange = ({ target }) => {
    setThisPassword(target.value);
  };
  const handleSubmit = async (e) => {
    e.preventDefault();
    Swal.fire({
      icon: "warning",
      text: "¿Seguro que quiere eliminar su cuenta?",

      showConfirmButton: true,
      showCancelButton: true,
      confirmButtonColor: "#f5990ff3",
      confirmButtonText: "Si",
      cancelButtonText: "No",
    }).then(async (result) => {
      if (result.isConfirmed) {
        const resp = await fetchApi("auth-customers.php?url=disableAccount", "PUT", {
          email: userData.email,
          password: thisPassword,
        });
        if (resp.status === "error") {
          Swal.fire({
            icon: "error",
            text: resp.result.error_msg,
            showConfirmButton: true,
            timer: 2000,
            confirmButtonColor: "#f5990ff3",
            confirmButtonText: "Ok",
          });
        }
        if (resp.status === "successfully") {
          localStorage.removeItem("token");
          navigate("/");
          window.location.reload();
        }
        console.log(resp);
      }
    });
  };

  return (
    <Animated
      animationIn="fadeIn"
      animationOut="fadeOutRight"
      animationInDuration={500}
      isVisible={true}
    >
      <form onSubmit={(e) => handleSubmit(e)}>
        <h2>Confirme su contraseña</h2>
        <p className="warning">
          <FontAwesomeIcon className="warningIcon" icon={faExclamationCircle} /> Cuidado, si
          desactivas tu cuenta no podrás volver a recuperarla
        </p>
        <div>
          <label>Contraseña</label>
          <input
            name="password"
            type="password"
            onChange={(e) => handleChange(e)}
            onBlur={(e) => handleChange(e)}
          />
        </div>
        <button className="submit-button" type="submit" disabled={thisPassword.length < 6}>
          Desactivar
        </button>
      </form>
    </Animated>
  );
};

export default DisableAccount;
