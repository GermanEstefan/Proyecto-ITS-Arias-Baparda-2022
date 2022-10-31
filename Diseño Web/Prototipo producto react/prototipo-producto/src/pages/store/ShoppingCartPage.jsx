/** @format */

import React, { useEffect, useState, useRef } from "react";
import NoPhoto from "../../assets/img/no-photo.png";
import PageTitle from "../../components/store/PageTitle";
import ContainerBase from "../../components/store/ContainerBase";
import CartItem from "../../components/store/CartItem";
import CartDetails from "../../components/store/CartDetails";
import { fetchApi } from "../../API/api";
import { useContext } from "react";
import { cartContext, userStatusContext } from "../../App";

import Input from "../../components/store/Input";
import Purchase from "../../assets/img/purchase.jpg";
import Select from "react-select";
import { useNavigate } from "react-router-dom";

const ShoppingCartPage = () => {
  const { cart, setCart } = useContext(cartContext);
  const { userData } = useContext(userStatusContext);
  const navigate = useNavigate();
  const [total, setTotal] = useState(0);
  const buyForm = useRef();
  const [values, setValues] = useState({
    email: userData.email,
    address: userData.address,
    deliveryTime: 1,
    paymentMenthod: null,
  });
  const [isAddressDisable, setIsAddressDisable] = useState(false);
  const [errorStatusForm, setErrorStatusForm] = useState({});

  const [productsList, setProductsList] = useState([]);
  const [storeHours, setStoreHours] = useState([]);
  const [deliveryHours, setDeliveryHours] = useState([]);
  const [isShipping, setIsShipping] = useState(true);

  useEffect(() => {
    window.scroll(0, 0);
    getProductsListByBarcode();
    getDeliveryHours();
    getStoreHours();
  }, []);
  useEffect(() => {
    setTotalPrice();
  }, [productsList]);

  const setAddress = (value) => {
    setValues({
      ...values,
      address: value,
    });
  };
  const setDeliveryTime = (value) => {
    setValues({
      ...values,
      deliveryTime: value,
    });
  };
  const setPaymentMethod = (value) => {
    setValues({
      ...values,
      paymentMenthod: value,
    });
  };

  const getProductsListByBarcode = async () => {
    const promises = [];
    cart.map(({ barcode }) => {
      promises.push(fetchApi(`products.php?barcode=${barcode}`, "GET"));
    });
    const responses = Promise.all(promises);
    const products = await responses;

    const productsData = products.map((product) => product.result.data);

    // Agregamos la cantidad de productos que tiene cada producto en el carrito a el objeto data
    cart.map(({ quantity }, index) => (productsData[index]["quantity"] = quantity));

    setProductsList(productsData);
    setTotalPrice();
  };

  const getStoreHours = async () => {
    const resp = await fetchApi("Deliverys.php?local", "GET");
    setStoreHours(
      resp.result.data.map((hourFromBack) => ({
        value: hourFromBack.id_local,
        label: hourFromBack.name,
      }))
    );
  };
  const getDeliveryHours = async () => {
    const resp = await fetchApi("Deliverys.php?delivery", "GET");
    setDeliveryHours(
      resp.result.data.map((hourFromBack) => ({
        value: hourFromBack.id_delivery,
        label: hourFromBack.name,
      }))
    );
  };

  const setTotalPrice = () => {
    setTotal(
      cart.length > 0 &&
        cart
          .map((product) => parseInt(product.price) * parseInt(product.quantity))
          .reduce((a, b) => parseInt(a) + parseInt(b))
    );
  };

  const goToCartBuyForm = () => {
    buyForm.current.scrollIntoView();
  };

  const paymentMethods = [
    { value: 0, label: "Efectivo" },
    { value: 1, label: "Online" },
  ];

  const handleConfirmPurchase = (e) => {
    e.preventDefault();
    const purchaseData = {
      address: values.address,
      client: userData.email,
      delivery: values.deliveryTime,
      payment: values.paymentMenthod,
      products: cart.map((product) => ({
        barcode: product.barcode,
        quantity: product.quantity,
      })),
    };
    setCart([]);
    console.log(purchaseData);
    fetchApi("sales.php", "POST", purchaseData);
  };

  const handleRadioChange = (value) => {
    if (value === "Dirección actual") {
      setAddress(userData.address);
      setIsAddressDisable(true);
      setIsShipping(true);
    }
    if (value === "Dirección alternativa") {
      setIsAddressDisable(false);
      setAddress("");
      setIsShipping(true);
    }
    if (value === "Retiro en local") {
      setAddress("Retiro en local");
      setIsAddressDisable(true);
      setIsShipping(false);
    }
  };
  const updateProductQuantity = (e, barcode) => {
    console.log(e.target.value);
    const cartWithNewQuantity = productsList.map((product) => {
      if (product.barcode === barcode) {
        return {
          ...product,
          quantity: parseInt(e.target.value),
        };
      } else {
        return product;
      }
    });
    // setProductTotalPrice(price * parseInt(e.target.value));
    setProductsList(cartWithNewQuantity);
    console.log(cartWithNewQuantity);
    setCart(cartWithNewQuantity);
    setTotalPrice();
  };
  return (
    <ContainerBase>
      <div className="cartContainer">
        <PageTitle title={"Carrito"} isArrow={true} goBack />
        <div className="cartPage">
          <CartDetails total={total || 0} onClick={goToCartBuyForm} />
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
              setProductsList={setProductsList}
              updateProductQuantity={updateProductQuantity}
            />
          ))}
          {productsList.length === 0 && (
            <span className="center">No tienes productos en tu carrito</span>
          )}
        </div>
      </div>

      <div ref={buyForm} style={{ paddingTop: "50px" }} className="form-container">
        <img className="form-img" src={Purchase} alt="Imagen" />
        <form>
          <h1>Confirma tu compra</h1>
          <div className="radioSection">
            <strong>Dirección de envío</strong>
            <div className="radioGroup" onChange={(e) => handleRadioChange(e.target.value)}>
              <label>
                <input
                  type="radio"
                  defaultChecked={true}
                  value={"Dirección actual"}
                  name="addressRadio"
                  id=""
                />{" "}
                Dirección actual
              </label>
              <label>
                <input type="radio" value={"Dirección alternativa"} name="addressRadio" id="" />{" "}
                Dirección alternativa
              </label>
              <label>
                <input type="radio" value={"Retiro en local"} name="addressRadio" id="" /> Retiro en
                local
              </label>
            </div>
            <Input
              name="address"
              id="address"
              value={values.address}
              placeholder="Dirección"
              onChange={(e) => setAddress(e.target.value)}
              setErrorStatusForm={setErrorStatusForm}
              className={"formInput"}
              disabled={isAddressDisable}
            />
          </div>
          <span className="mt-5">{isShipping ? "Horarios de envío" : "Horarios del local"}</span>
          <Select
            name="deliveryTime"
            id="deliveryTime"
            className="select"
            onChange={(e) => setDeliveryTime(e.value)}
            options={isShipping ? deliveryHours : storeHours}
            placeholder={"Horario"}
            // defaultValue={isShipping ? deliveryHours[0] : storeHours[0]}
          />
          <span className="mt-5">{"Metodo de pago"}</span>
          <Select
            name="paymentMenthod"
            id="paymentMenthod"
            className="select"
            onChange={(e) => setPaymentMethod(e.value)}
            options={paymentMethods}
            placeholder={"Metodo de pago"}
          />
          <button
            className="submit-button"
            onClick={(e) => handleConfirmPurchase(e)}
            style={{ width: "65%" }}
            type="submit"
            disabled={
              values.address === "" ||
              values.deliveryTime === null ||
              values.paymentMenthod === null
            }
          >
            Confirmar
          </button>
        </form>
      </div>
      <div style={{ height: "12vh" }}></div>
    </ContainerBase>
  );
};

export default ShoppingCartPage;
