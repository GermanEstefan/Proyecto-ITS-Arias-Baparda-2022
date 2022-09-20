import React, { useEffect } from "react";
import Imagen from "../../assets/img/Obreros.jpg";
import Swal from "sweetalert2";
import ContainerBase from "../../components/store/ContainerBase";

const Contact = () => {

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
      <main className="form-container main-client">
        <img className="form-img" src={Imagen} alt="Imagen"></img>
        <form className="form" onSubmit={handleSubmit} autoComplete="off">
          <h1>Envianos tu mensaje</h1>
          <input
            name="topic"
            id="topic"
            placeholder="Asunto"
            className="input"
          />

          <textarea
            name="message"
            id="message"
            rows={7}
            maxLength={130}
            placeholder="Mensaje"
          ></textarea>

          <button
            className="submit-button"
            type="submit"
            to={"/register"}
          >Enviar</button>

        </form>
      </main>
    </ContainerBase>
  );
};

export default Contact;
