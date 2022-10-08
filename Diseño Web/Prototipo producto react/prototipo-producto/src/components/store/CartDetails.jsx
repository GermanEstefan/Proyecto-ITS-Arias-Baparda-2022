import React from "react";
import { useMediaQuery } from "react-responsive";

const CartDetails = ({ total }) => {

  return (
    <>
      <div className="cartItem">
        <div className="cartItem__text">
          <p>Total: {total}$</p>
        </div>
      </div>
    </>
  );
};

export default CartDetails;
