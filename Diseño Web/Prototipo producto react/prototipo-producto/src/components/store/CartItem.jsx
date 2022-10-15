import React, { useState, useEffect, useContext } from "react";
import { Animated } from "react-animated-css";
import { faTrash } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";

const CartItem = ({ img, product, precio, barcode, index}) => {
  const [cart, setCart] = useState(() => {
    const saved = localStorage.getItem("cart");
    const initialValue = JSON.parse(saved);
    return initialValue || [];
  });

  const handleDeleteItemFromCart = (e) => {
    e.preventDefault();
    console.log(cart);
    console.log(index)
    setCart(cart.filter(barcodeObj => barcodeObj.barcode !== barcode));
    localStorage.setItem("cart", JSON.stringify(cart));
  };

  return (
    <Animated
      animationIn="fadeInLeft"
      animationOut="fadeOut"
      animationInDuration="500"
      isVisible={true}
    >
      <div className="cartItem">
        <img className="cartItem__img" src={img} width="100px" alt="" />

        <div className="cartItem__text">
          <h2>{product}</h2>
          <p>{precio}$</p>
        </div>
        <div className="CartItem__actions">
          <input className="CartItem__actionsAmount" value={1} type="number" />
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
