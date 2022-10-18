import React, { useState, useEffect, useContext } from "react";
import { Animated } from "react-animated-css";
import { faTrash } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { fetchApi } from "../../API/api";
import Guantes from "../../assets/img/guantes.jpg";

const CartItem = ({ barcode, img, name, price, onChangeAmount, value }) => {
  const [cart, setCart] = useState(
    JSON.parse(localStorage.getItem("cart") || [])
  );

  const [product, setProduct] = useState({});

  useEffect(() => {
    getProductByBarcode();
  }, []);

  const handleDeleteItemFromCart = (e) => {
    e.preventDefault();
    setCart(cart.filter((barcode) => barcode !== barcode));
    localStorage.setItem("cart", JSON.stringify(cart));
  };

  const getProductByBarcode = async () => {
    const resp = await fetchApi(`products.php?barcode=${barcode}`, "GET");
    setProduct(resp.result.data);
  };

  return (
    <Animated
      animationIn="fadeInLeft"
      animationOut="fadeOut"
      animationInDuration={500}
      isVisible={true}
    >
      <div className="cartItem">
        <img className="cartItem__img" src={img} width="100px" alt="" />

        <div className="cartItem__text">
          <h2>{name}</h2>
          <p>{price}$</p>
        </div>
        <div className="CartItem__actions">
          <input
            className="CartItem__actionsAmount"
            value={value}
            type="number"
          />
          <button>Pagar</button>
          <button
            className="CartItem__actionsAmount"
            onClick={(e) => handleDeleteItemFromCart(e)}
          >
            <FontAwesomeIcon icon={faTrash} />
          </button>
        </div>
      </div>
    </Animated>
  );
};

export default CartItem;
