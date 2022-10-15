import React, { useState, useEffect, useContext } from "react";

import { useParams } from "react-router-dom";
import Guantes from "../../assets/img/guantes.jpg";
import PageTitle from "../../components/store/PageTitle";
import ContainerBase from "../../components/store/ContainerBase";
import { fetchApi } from "../../API/api";
import { userStatusContext } from "../../App";
const ProductPage = () => {
  const { userData } = useContext(userStatusContext);
  const { category, id } = useParams();

  const [cart, setCart] = useState( JSON.parse(localStorage.getItem("cart") || []) );
  const [product, setProduct] = useState({});

  useEffect(() => {
    window.scroll(0, 0);
    getProductById();
  }, []);

  const getProductById = async () => {
    const resp = await fetchApi(`products.php?idProduct=${id}`, "GET");
    console.log(resp);
    setProduct(resp.result.data[0] ? resp.result.data[0] : {});
  };

  const handleAddToCart = () => {
    setCart([...cart, { barcode: product.barcode }]);
    console.log('habdl')
    localStorage.setItem("cart", JSON.stringify(cart));
  };

  return (
    <ContainerBase>
      <div className="productContainer">
        <PageTitle
          title={product.name}
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
              <p>{product.price}$</p>
              <p>{product.description}</p>
            </div>
            <div className="productPage__description__buttons">
              <button className="buyBtn" disabled={!userData.auth}>
                Comprar
              </button>
              <button
                className="addBtn"
                // disabled={cart.find(
                //   (barcode) => barcode.barcode == product.barcode
                // )}
                onClick={handleAddToCart}
              >
                Agregar al carrito
              </button>
              {cart.find((barcode) => barcode.barcode === product.barcode) && (
                <p>Este producto ya está en tu carrito</p>
              )}
            </div>
          </div>
        </div>
      </div>
    </ContainerBase>
  );
};

export default ProductPage;
