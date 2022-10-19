import React, { useState, useEffect } from "react";
import { faShoppingCart } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { useMediaQuery } from "react-responsive";
import { useNavigate } from "react-router-dom";

const ShoppingCart = () => {
  const navigate = useNavigate();

  const isMobile = useMediaQuery({ query: "(max-width: 800px)" });

  const [cart, setCart] = useState(() => {
    const saved = localStorage.getItem("cart");
    const initialValue = JSON.parse(saved);
    return initialValue || [{}];
  });
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
