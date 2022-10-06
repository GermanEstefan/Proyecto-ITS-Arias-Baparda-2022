import React, { useState, useEffect, useContext } from "react";

import { useParams } from "react-router-dom";
import Guantes from "../../assets/img/guantes.jpg";
import PageTitle from "../../components/store/PageTitle";
import ContainerBase from "../../components/store/ContainerBase";
import { userStatusContext } from "../../App";
const ProductPage = () => {
  const { userData } = useContext(userStatusContext);
  const { category, id } = useParams();
  const [cart, setCart] = useState(() => {
    const saved = localStorage.getItem("cart");
  const initialValue = JSON.parse(saved);
  return initialValue || [{}];
  });
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
  const handleAddToCart = () => {
    console.log("add");

    setCart([...cart, { id: new Date() }]);
    localStorage.setItem("cart", JSON.stringify(cart));
  };

  return (
    <ContainerBase>
      <div className="productContainer">
        <PageTitle
          title={productMock.name}
          isArrow={true}
          arrowGoTo={`/category/${category}`}
        />
        <div className="productPage">
          <div className="productPage__img">
            <div>
              <img width={"300px"} src={Guantes} alt="Imagen del producto" />
            </div>
          </div>
          <div className="productPage__description">
            <div className="productPage__description__body">
              <p>{productMock.price}$</p>
              <p>{productMock.description}</p>
            </div>
            <div className="productPage__description__buttons">
              <button className="buyBtn" disabled={!userData.auth}>
                Comprar
              </button>
              <button
                className="addBtn"
                disabled={userData.auth}
                onClick={handleAddToCart}
              >
                Agregar al carrito
              </button>
              {!userData.auth && (
                <p>Registrate e ingresa para comenzar a comprar</p>
              )}
            </div>
          </div>
        </div>
      </div>
    </ContainerBase>
  );
};

export default ProductPage;
