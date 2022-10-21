import React, { useState, useEffect, useContext } from "react";
import { faShoppingCart } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { useMediaQuery } from "react-responsive";
import { useNavigate } from "react-router-dom";
import { cartContext } from "../../App";

const ShoppingCart = () => {
  const navigate = useNavigate();

  const isMobile = useMediaQuery({ query: "(max-width: 800px)" });

  const { cart, setCart } = useContext(cartContext);

  const [cartLength, setCartLength] = useState(cart.length);
  useEffect(() => {
    setCartLength(cart.length);
  }, [cart]);

  return (
    <div
      className={isMobile ? "shopping-cart-mobile" : "shopping-cart-desktop"}
    >
      <span>{cartLength || 0}</span>
      <FontAwesomeIcon
        icon={faShoppingCart}
        onClick={() => navigate("/shoppingCart")}
      />
    </div>
  );
};

export default ShoppingCart;
