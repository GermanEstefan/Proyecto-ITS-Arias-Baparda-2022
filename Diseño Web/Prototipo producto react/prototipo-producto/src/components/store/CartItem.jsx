import React, { useState, useEffect } from "react";
import { Animated } from "react-animated-css";
import { faTrash } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { fetchApi } from "../../API/api";
import Guantes from "../../assets/img/guantes.jpg";
import { useContext } from "react";
import { cartContext } from "../../App";

const CartItem = ({ barcode, img, name, price, quantity, setTotalPrice, size, design }) => {
  const { cart, setCart } = useContext(cartContext);

  const [product, setProduct] = useState({});

  useEffect(() => {
    getProductByBarcode();
  }, []);

  const handleDeleteItemFromCart = (e) => {
    e.preventDefault();
    setCart(cart.filter((barcode) => barcode !== barcode));
    localStorage.setItem("cart", JSON.stringify(cart));
  };

  const getProductByBarcode = async () => {
    const resp = await fetchApi(`products.php?barcode=${barcode}`, "GET");
    setProduct(resp.result.data);
  };

  const updateProductQuantity = (e) => {
    e.preventDefault();
    const cartWithNewQuantity = cart.map((product) => {
      if (product.barcode === barcode) {
        return { barcode: barcode, quantity: e.target.value };
      } else {
        return product;
      }
    });
    setCart(cartWithNewQuantity);
    setTotalPrice();
  };
  return (
    <Animated
      animationIn="fadeInLeft"
      animationOut="fadeOut"
      animationInDuration={500}
      isVisible={true}
    >
      <div className="cartItem">
        <img className="cartItem__img" src={img} width="100px" alt="" />

        <div className="cartItem__text">
          <div>
          <h3>{name}</h3>
          <span>Talle: {size} Color: {design} </span>
          </div>
          <p>{price * quantity}$</p>
          
        </div>
        <div className="CartItem__actions">
          <input
            className="CartItem__actionsQuantity"
            defaultValue={quantity}
            type="number"
            onChange={updateProductQuantity}
            min={1}
          />
          <button
            className="CartItem__actionsQuantity"
            onClick={(e) => handleDeleteItemFromCart(e)}
          >
            <FontAwesomeIcon icon={faTrash} />
          </button>
        </div>
      </div>
    </Animated>
  );
};

export default CartItem;
