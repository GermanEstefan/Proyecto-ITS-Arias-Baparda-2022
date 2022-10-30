import React from "react";

const CartDetails = ({ total, onClick }) => {

  return (
    <>
      <div className="cartItem">
        <div className="cartItem__text">
          <span className="total">Total del carrito: {total}$</span>
          <button className="buyBtn" onClick={onClick}>Confirmar compra</button>
        </div>
      </div>
    </>
  );
};

export default CartDetails;
