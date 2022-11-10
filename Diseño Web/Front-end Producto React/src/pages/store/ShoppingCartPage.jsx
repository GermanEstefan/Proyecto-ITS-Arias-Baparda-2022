/** @format */

import React, { useEffect, useState, useRef } from "react";
import NoPhoto from "../../assets/img/no-photo.png";
import PageTitle from "../../components/store/PageTitle";
import ContainerBase from "../../components/store/ContainerBase";
import CartItem from "../../components/store/CartItem";
import CartDetails from "../../components/store/CartDetails";
import { fetchApi } from "../../API/api";
import { useContext } from "react";
import { cartContext } from "../../App";
import NoData from "../../components/store/NoData";

const ShoppingCartPage = () => {
  const { cart, setCart } = useContext(cartContext);

  const [total, setTotal] = useState(0);

  const [productsList, setProductsList] = useState([]);

  useEffect(() => {
    window.scroll(0, 0);
    getProductsListByBarcode();
  }, []);
  useEffect(() => {
    setTotalPrice();
  }, [productsList]);

  const getProductsListByBarcode = async () => {
    const promises = [];
    try {
      cart.map(({ barcode }) => {
        promises.push(fetchApi(`products.php?barcode=${barcode}`, "GET"));
      });
      const responses = Promise.all(promises);
      const products = await responses;

      const productsData = products.map((product) => product.result.data);

      cart.map(({ quantity }, index) => (productsData[index]["quantity"] = quantity));

      setProductsList(productsData);
      setTotalPrice();
      
    } catch (error) {
      console.error(error);
      alert("ERROR, comunicarse con el administrador");
    }
  };

  const setTotalPrice = () => {
    setTotal(
      cart.length > 0 && cart.map((product) => parseInt(product.price) * parseInt(product.quantity)).reduce((a, b) => parseInt(a) + parseInt(b))
    );
  };

  const updateProductQuantity = (e, barcode, stock) => {
    
    if (e.target.value == "" || parseInt(e.target.value) <= stock) {
      const cartWithNewQuantity = productsList.map((product) => {
        if (product.barcode === barcode) {
          return {
            ...product,
            quantity: e.target.value != "" && parseInt(e.target.value),
          };
        } else {
          return product;
        }
      });

      setProductsList(cartWithNewQuantity);

      setCart(cartWithNewQuantity);
      setTotalPrice();
    }
  };
  const handleDeleteItemFromCart = (barcode) => {
    setCart(cart.filter((product) => product.barcode !== barcode));
    setProductsList(cart.filter((product) => product.barcode !== barcode));
    window.location.reload(false);
  };

  return (
    <ContainerBase>
      <div className="cartContainer">
        <PageTitle title={"Carrito"} isArrow goBack />
        <div className="cartPage">
          <CartDetails total={total || 0} isCartEmpty={productsList.length === 0} />
          {productsList.map((product, index) => (
            <CartItem
              key={index}
              img={product.picture ? product.picture : NoPhoto}
              barcode={product.barcode}
              name={product.name}
              price={product.price}
              quantity={product.quantity}
              setTotalPrice={setTotalPrice}
              size={product.size}
              design={product.design}
              stock={product.stock}
              setProductsList={setProductsList}
              updateProductQuantity={updateProductQuantity}
              handleDeleteItemFromCart={handleDeleteItemFromCart}
              category={product.category}
              id={product.id_product}
            />
          ))}
          {productsList.length === 0 && <NoData message="No tienes productos en tu carrito" />}
        </div>
      </div>
    </ContainerBase>
  );
};

export default ShoppingCartPage;
