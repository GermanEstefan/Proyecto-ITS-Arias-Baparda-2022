/** @format */

import React from "react";
import { useContext } from "react";
import { useNavigate } from "react-router-dom";
import Swal from "sweetalert2";
import { userStatusContext } from "../../App";

const CartDetails = ({ total, isCartEmpty }) => {

  const { auth } = useContext(userStatusContext).userData;

  const navigate = useNavigate();
  const handleClick = () => {
    if (!auth) {
      return Swal.fire({
        icon: "warning",
        text: "Debes iniciar sesion para efecutar una compra",
        showConfirmButton: true,
        confirmButtonColor: "#f5990ff3",
      }).then( res => res.isConfirmed ? navigate('/login') : null )
    }
    navigate("/buyForm");
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
