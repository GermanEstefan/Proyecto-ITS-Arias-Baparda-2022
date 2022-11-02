import React from "react";
import { useNavigate } from "react-router-dom";

const CartDetails = ({ total }) => {
const naviage = useNavigate()
  return (
    <>
      <div className="cartItem">
        <div className="cartItem__text">
          <span className="total">Total del carrito: {total}$</span>
          <button className="buyBtn" onClick={() => naviage('/buyForm')}>Confirmar compra</button>
        </div>
      </div>
    </>
  );
};

export default CartDetails;
