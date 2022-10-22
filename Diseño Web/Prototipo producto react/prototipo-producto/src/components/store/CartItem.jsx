import React, { useState, useEffect } from "react";
import { Animated } from "react-animated-css";
import { faTrash } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { fetchApi } from "../../API/api";
import Guantes from "../../assets/img/guantes.jpg";
import { useContext } from "react";
import { cartContext } from "../../App";

const CartItem = ({ barcode, img, name, price, amount, setTotalPrice }) => {
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

  const updateProductAmount = (e) => {
    e.preventDefault();
    const cartWithNewAmount = cart.map((product) => {
      if (product.barcode === barcode) {
        return { barcode: barcode, amount: e.target.value };
      } else {
        return product;
      }
    });
    setCart(cartWithNewAmount);
    setTotalPrice()
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
          <h2>{name}</h2>
          <p>{price}$</p>
        </div>
        <div className="CartItem__actions">
          <input
            className="CartItem__actionsAmount"
            defaultValue={amount}
            type="number"
            onChange={updateProductAmount}
            min={1}
          />
          <button
            className="CartItem__actionsAmount"
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
