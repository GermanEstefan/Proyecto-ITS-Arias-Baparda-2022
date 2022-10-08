import React from "react";
import { Animated } from "react-animated-css";
import { faTrash } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";

const CartItem = ({ img, product, precio, id }) => {

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
          <input className="CartItem__actionsAmount" type="number" />
          <button>Pagar</button>
          <button className="CartItem__actionsAmount">
            <FontAwesomeIcon icon={faTrash} />
          </button>
        </div>
      </div>
    </Animated>
  );
};

export default CartItem;
