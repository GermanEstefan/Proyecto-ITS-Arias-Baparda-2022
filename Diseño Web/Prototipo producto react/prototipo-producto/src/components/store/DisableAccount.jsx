/** @format */

import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import React, { useState } from "react";
import { useContext } from "react";
import { Animated } from "react-animated-css";
import Swal from "sweetalert2";
import { useNavigate } from "react-router-dom";
import { fetchApi } from "../../API/api";
import { userStatusContext } from "../../App";
import { faExclamationCircle, faEye, faEyeSlash } from "@fortawesome/free-solid-svg-icons";

const DisableAccount = () => {
  const navigate = useNavigate();
  const { userData } = useContext(userStatusContext);
  const [thisPassword, setThisPassword] = useState("");
  const [viewPassword, setViewPassword] = useState(true);
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
          <div style={{ display: "flex", justifyContent: "flex-end", flexDirection: 'row' }}>
            <input
              name="password"
              type={viewPassword ? "password" : "text"}
              onChange={(e) => handleChange(e)}
              onBlur={(e) => handleChange(e)}
              style={{width: '100%'}}
            />

            <FontAwesomeIcon
              style={{
                margin: '10px 0 15px -10px',
                position: "absolute",
                cursor: "pointer",
              }}
              onClick={() => setViewPassword(!viewPassword)}
              icon={!viewPassword ? faEye : faEyeSlash}
              color="gray"
            />
          </div>
        </div>
        <button className="submit-button" type="submit" disabled={thisPassword.length < 6}>
          Desactivar
        </button>
      </form>
    </Animated>
  );
};

export default DisableAccount;
