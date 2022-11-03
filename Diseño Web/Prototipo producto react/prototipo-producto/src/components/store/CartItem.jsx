/** @format */

import React, { useState, useEffect } from "react";
import { Animated } from "react-animated-css";
import { faTrash } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";

import NoPhoto from "../../assets/img/no-photo.png";

const CartItem = ({
  barcode,
  img,
  name,
  price,
  quantity,
  size,
  design,
  updateProductQuantity,
  handleDeleteItemFromCart,
  stock,
}) => {
  const [productTotalPrice, setProductTotalPrice] = useState(price);
  const handleSetPrice = (e) => {
    
    if (parseInt(e.target.value) > 0 && e.target.value != '' && parseInt(e.target.value) <= stock) {
      const thisPrice = price * parseInt(e.target.value);
      setProductTotalPrice(thisPrice);
    }
    if(e.target.value == ''){
      setProductTotalPrice(0);
    }
  };
  return (
    <Animated
      animationIn="fadeInLeft"
      animationOut="fadeOut"
      animationInDuration={500}
      isVisible={true}
    >
      <div className="cartItem">
        <img className="cartItem__img" src={img ? img : NoPhoto} width="100px" alt="" />

        <div className="cartItem__text">
          <div>
            <h3>{name}</h3>
            <span>
              Talle: {size}, Color: {design}
            </span>
          </div>
          <p>{productTotalPrice}$</p>
        </div>
        <div className="CartItem__actions">
          <input
            className="CartItem__actionsQuantity"
            defaultValue={quantity}
            value={quantity}
            type="number"
            onkeydown={false}
            onChange={(e) => {
              updateProductQuantity(e, barcode, stock);
              handleSetPrice(e);
            }}
            min={1}
            max={stock}
          />
          <button
            className="CartItem__actionsQuantity"
            onClick={() => handleDeleteItemFromCart(barcode)}
          >
            <FontAwesomeIcon icon={faTrash} />
          </button>
        </div>
      </div>
    </Animated>
  );
};

export default CartItem;
