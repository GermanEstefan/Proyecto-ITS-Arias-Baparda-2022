/** @format */

import React from "react";
import { useNavigate } from "react-router-dom";

const CartDetails = ({ total, isCartEmpty }) => {
  console.log(isCartEmpty);
  const naviage = useNavigate();
  const handleClick = () => {
    if (!isCartEmpty) {
      naviage("/buyForm");
    }
  };
  return (
    <>
      <div className="cartItem">
        <div className="cartItem__text">
          <span className="total">Total del carrito: ${total}</span>
          <button className="buyBtn" onClick={() => handleClick()} disabled={isCartEmpty} >
            Confirmar compra
          </button>
        </div>
      </div>
    </>
  );
};

export default CartDetails;
