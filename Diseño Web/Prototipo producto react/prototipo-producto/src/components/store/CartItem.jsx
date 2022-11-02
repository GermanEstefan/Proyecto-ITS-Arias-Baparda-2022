/** @format */

import React, { useState, useEffect } from "react";
import { Animated } from "react-animated-css";
import { faTrash } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";

import NoPhoto from "../../assets/img/no-photo.png";

const CartItem = ({
  barcode,
  img,
  name,
  price,
  quantity,
  size,
  design,
  updateProductQuantity,
  handleDeleteItemFromCart,
}) => {
  // const [product, setProduct] = useState({});
  const [productTotalPrice, setProductTotalPrice] = useState(price);

  // useEffect(() => {
  //   getProductByBarcode();
  // }, []);

  // const handleDeleteItemFromCart = () => {
  //   setProductsList(cart.filter((product) => product.barcode !== barcode));
  //   setCart(cart.filter((product) => product.barcode !== barcode));

  // };

  // const getProductByBarcode = async () => {
  //   const resp = await fetchApi(`products.php?barcode=${barcode}`, "GET");
  //   setProduct(resp.result.data);
  // };
  return (
    <Animated
      animationIn="fadeInLeft"
      animationOut="fadeOut"
      animationInDuration={500}
      isVisible={true}
    >
      <div className="cartItem">
        <img className="cartItem__img" src={img ? img : NoPhoto} width="100px" alt="" />

        <div className="cartItem__text">
          <div>
            <h3>{name}</h3>
            <span>
              Talle: {size} Color: {design}
            </span>
          </div>
          <p>{productTotalPrice}$</p>
        </div>
        <div className="CartItem__actions">
          <input
            className="CartItem__actionsQuantity"
            defaultValue={quantity}
            type="number"
            onChange={(e) => {
              updateProductQuantity(e, barcode);
              setProductTotalPrice(price * parseInt(e.target.value));
            }}
            min={1}
          />
          <button className="CartItem__actionsQuantity" onClick={() => handleDeleteItemFromCart(barcode)}>
            <FontAwesomeIcon icon={faTrash} />
          </button>
        </div>
      </div>
    </Animated>
  );
};

export default CartItem;
