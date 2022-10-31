/** @format */

import React, { useEffect, useState } from "react";
import Imagen from "../../assets/img/Obreros.jpg";
import Swal from "sweetalert2";
import ContainerBase from "../../components/store/ContainerBase";

const Contact = () => {
  const [message, setMessage] = useState("");
  useEffect(() => {
    window.scroll(0, 0);
  }, []);

  const handleSubmit = (e) => {
    e.preventDefault();
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
              style={{ width: "100%" }}
              type="text"
              name="topic"
              id="topic"
              placeholder="Asunto"
            />
          </div>
          <div className="textareaSection">
            <textarea
              name="message"
              id="message"
              onChange={(e) => setMessage(e.target.value)}
              rows={7}
              maxLength={250}
              placeholder="Mensaje"
            />
            <blockquote>{message.length}/250</blockquote>
          </div>
          <button className="submit-button" type="submit" to={"/register"}>
            Enviar
          </button>
        </form>
      </main>
    </ContainerBase>
  );
};

export default Contact;
