/** @format */

import React, { useEffect, useState } from "react";
import Imagen from "../../assets/img/Obreros.jpg";
import Swal from "sweetalert2";
import { fetchApi } from "../../API/api";
import ContainerBase from "../../components/store/ContainerBase";
import { useContext } from "react";
import { userStatusContext } from "../../App";
import { isEmail } from "../../helpers/validateForms";

const Contact = () => {
  const { userData } = useContext(userStatusContext);

  const initialValues = {
    client: userData.email || "",
    subject: "",
    text: "",
  };

  const initialValuesErrors = {
    client: { error: false, message: null },
    subject: { error: false, message: null },
    text: { error: false, message: null },
  };
  const [values, setValues] = useState(initialValues);
  const [errors, setErrors] = useState(initialValuesErrors);

  useEffect(() => {
    window.scroll(0, 0);
  }, []);

  const handleSetValues = ({ target }) => {
    if (target.name === "client") {
      setErrors({ ...errors, [target.name]: isEmail(target.value) });
    }

    setValues({ ...values, [target.name]: target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    console.log(values)
    const resp = await fetchApi("auth-customers.php?url=consult", "POST", values);
    console.log(resp)
    Swal.fire({
      icon: "warning",
      text: "Esta funcionalidad aún no está implementada",
      timer: 1500,
      showConfirmButton: false,
    });
  };
  return (
    <ContainerBase>
      <main className="form-container">
        <img src={Imagen} alt="Imagen" />
        <form onSubmit={handleSubmit} autoComplete="off">
          <h1>Envianos tu mensaje</h1>
          <div className="inputSection">
            <input
              style={
                errors.client.error ? { borderColor: "red", width: "100%" } : { width: "100%" }
              }
              type="text"
              name="client"
              value={userData.email && userData.email}
              disabled={userData.email}
              placeholder="Mail"
              onChange={(e) => handleSetValues(e)}
              onBlur={(e) => handleSetValues(e)}
            />
          </div>
          <span className="spanError">{errors.client.message}</span>
          <div className="inputSection">
            <input
              style={{ width: "100%" }}
              type="text"
              name="subject"
              placeholder="Asunto"
              onChange={(e) => handleSetValues(e)}
              onBlur={(e) => handleSetValues(e)}
            />
          </div>
          <div className="textareaSection">
            <textarea
              name="text"
              rows={7}
              maxLength={250}
              placeholder="Mensaje"
              onChange={(e) => handleSetValues(e)}
              onBlur={(e) => handleSetValues(e)}
            />
            <blockquote>{values.text.length}/250</blockquote>
          </div>
          <button className="submit-button" type="submit" disabled={errors.client.error}>
            Enviar
          </button>
        </form>
      </main>
    </ContainerBase>
  );
};

export default Contact;
