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
import Imagen from "../../assets/img/Obreros.jpg";
import Select from "react-select";

const ShoppingCartPage = () => {
  const { cart, setCart } = useContext(cartContext);
  const { userData } = useContext(userStatusContext);

  const [total, setTotal] = useState(0);
  const buyForm = useRef();
  const [values, setValues] = useState({
    email: userData.email,
    address: userData.address,
    deliveryTime: null,
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
    getStoreHour();
    getDeliveryHour();
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
    cart.map(
      ({ quantity }, index) => (productsData[index]["quantity"] = quantity)
    );

    setProductsList(productsData);
    setTotalPrice();
  };

  const getStoreHour = async () => {
    const resp = await fetchApi("Deliverys.php?local", "GET");
    setStoreHours(
      resp.result.data.map((hourFromBack) => ({
        value: hourFromBack.id_delivery,
        label: hourFromBack.name,
      }))
    );
  };

  const getDeliveryHour = async () => {
    const resp = await fetchApi("Deliverys.php?delivery", "GET");
    setDeliveryHours(resp.result.data);
  };
  const setTotalPrice = () => {
    setTotal(
      cart.length > 0 &&
        cart
          .map(
            (product) => parseInt(product.price) * parseInt(product.quantity)
          )
          .reduce((a, b) => parseInt(a) + parseInt(b))
    );
  };

  const goToCartBuyForm = () => {
    buyForm.current.scrollIntoView();
  };

  const deliveryTimes = [
    { value: 1, label: "08:00 - 12:00" },
    { value: 2, label: "12:00 - 16:00" },
    { value: 3, label: "16:00 - 20:00" },
  ];
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
    setCart([])
    console.log(purchaseData)
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

  return (
    <ContainerBase>
      <div className="cartContainer">
        <PageTitle title={"Carrito"} isArrow={true} arrowGoTo={`/`} />
        <div className="cartPage">
          <CartDetails total={total || 0} onClick={goToCartBuyForm} />
          {productsList.map((product, index) => (
            <CartItem
              key={index}
              img={product.picture ? product.picture.split("&")[0] : NoPhoto}
              barcode={product.barcode}
              name={product.name}
              price={product.price}
              quantity={product.quantity}
              setTotalPrice={setTotalPrice}
              size={product.size}
              design={product.design}
            />
          ))}
          {productsList.length === 0 && (
            <span className="center">No tienes productos en tu carrito</span>
          )}
        </div>
        <div ref={buyForm} className="form-container">
          <img
            className="form-img"
            src={Imagen ? Imagen : NoPhoto}
            alt="Imagen"
          />
          <form>
            <h1>Confirma tu compra</h1>
            <div className="radioSection">
              <strong>Dirección de envío</strong>
              <div
                className="radioGroup"
                onChange={(e) => handleRadioChange(e.target.value)}
              >
                <label>
                  <input
                    type="radio"
                    value={"Dirección actual"}
                    name="addressRadio"
                    id=""
                  />{" "}
                  Dirección actual
                </label>
                <label>
                  <input
                    type="radio"
                    value={"Dirección alternativa"}
                    name="addressRadio"
                    id=""
                  />{" "}
                  Dirección alternativa
                </label>
                <label>
                  <input
                    type="radio"
                    value={"Retiro en local"}
                    name="addressRadio"
                    id=""
                  />{" "}
                  Retiro en local
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
            {/* Los rangos horarios van a venir por endpoint */}
            <span className="mt-5">
              {isShipping ? "Horarios de envío" : "Horarios del local"}
            </span>
            <Select
              name="deliveryTime"
              id="deliveryTime"
              className="select"
              onChange={(e) => setDeliveryTime(e.value)}
              options={isShipping ? deliveryTimes : storeHours}
              placeholder={"Horario"}
              // defaultValue={isShipping ? deliveryTimes[0] : storeHours[0]}
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
      </div>
    </ContainerBase>
  );
};

export default ShoppingCartPage;
