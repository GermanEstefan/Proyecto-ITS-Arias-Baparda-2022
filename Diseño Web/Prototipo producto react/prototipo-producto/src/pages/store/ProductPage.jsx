import React, { useEffect } from "react";
import { useParams } from "react-router-dom";
import Guantes from "../../assets/img/guantes.jpg";
import PageTitle from "../../components/store/PageTitle";
import Carousel from "react-responsive-carousel";
import ContainerBase from "../../components/store/ContainerBase";

const ProductPage = () => {
  const { category, id } = useParams();
  useEffect(() => {
    window.scroll(0, 0);
  }, []);

  // Usar el id con un endpoint para traer todos los datos del producto a mostrar

  const productMock = {
    name: "Su producto",
    price: "10.500",
    description:
      "Lorem ipsum dolor sit, amet consectetur adipisicing elit. Deleniti facilis labore modi quo sapiente expedita nesciunt quos deseruntnobis provident",
  };

  return (
    <ContainerBase>
      <div className="productPage">
        <div className="productPage__img">
          <div>
            <img width={'300px'} src={Guantes} alt="Imagen del producto" />
          </div>
        </div>
        <div className="productPage__description">
          <PageTitle
            title={productMock.name}
            isArrow={true}
            arrowGoTo={`/category/${category}`}
          />

          <div className="productPage__description__body">
            <p>{productMock.price}$</p>
            <p>{productMock.description}</p>
          </div>
          <div className="productPage__description__buttons">
            <button className="buyBtn">Comprar</button>
            <button className="addBtn">Agregar al carrito</button>
          </div>
        </div>
      </div>
    </ContainerBase>
  );
};

export default ProductPage;
