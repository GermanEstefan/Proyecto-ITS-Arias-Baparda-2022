import React, { useEffect } from "react";
import { useParams } from "react-router-dom";
import Guantes from "../../assets/img/guantes.jpg";
import { faCartPlus } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";

const ProductPage = () => {
  const { id } = useParams();
  useEffect(() => {
    window.scroll(0, 0);
  }, []);
  console.log(id);

  // Usar el id con un endpoint para traer todos los datos del producto a mostrar

  const porductMock = {
    name: "Su producto",
    description:
      "Lorem ipsum dolor sit, amet consectetur adipisicing elit. Deleniti facilis labore modi quo sapiente expedita nesciunt quos deseruntnobis provident",
  };

  return (
    <div className="productPage">
      <div className="productPage__img">
        <img src={Guantes} alt="Imagen del producto" />
      </div>
      <div className="productPage__description">
        <div className="productPage__description__header">
          <h1>
            {porductMock.name}{" "}
          </h1>
            <a href="">
              <FontAwesomeIcon icon={faCartPlus} size='2x'/>
            </a>
        </div>
        <p>{porductMock.description}</p>
      </div>
    </div>
  );
};

export default ProductPage;
