import React, { useState } from "react";
import { faShoppingCart } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { useMediaQuery } from "react-responsive";
import { useNavigate } from "react-router-dom";

const ShoppingCart = () => {
  const navigate = useNavigate();

  const isMobile = useMediaQuery({ query: "(max-width: 800px)" });

  const [cart] = useState(() => {
    const saved = localStorage.getItem("cart");
    const initialValue = JSON.parse(saved);
    return initialValue || [];
  });


  return (
    <div
      className={isMobile ? "shopping-cart-mobile" : "shopping-cart-desktop"}
    >
      <span>{cart.length || 0}</span> {/*Este numero va a ser dinamico*/}
      <FontAwesomeIcon
        icon={faShoppingCart}
        onClick={() => navigate("/shoppingCart")}
      />
    </div>
  );
};

export default ShoppingCart;
