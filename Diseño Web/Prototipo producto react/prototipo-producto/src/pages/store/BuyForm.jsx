/** @format */

import React, { useEffect, useState, useContext } from "react";
import { fetchApi } from "../../API/api";
import Swal from "sweetalert2";
import Input from "../../components/store/Input";
import Purchase from "../../assets/img/purchase.jpg";
import Select from "react-select";
import { cartContext, userStatusContext } from "../../App";
import ContainerBase from "../../components/store/ContainerBase";
import { useNavigate } from "react-router-dom";

const paymentMethods = [
  { value: 0, label: "Efectivo" },
  { value: 1, label: "Online" },
];

const BuyForm = () => {
  const { cart, setCart } = useContext(cartContext);
  const navigate = useNavigate();
  const { userData } = useContext(userStatusContext);
  const [isAddressDisable, setIsAddressDisable] = useState(true);
  const [storeHours, setStoreHours] = useState([]);
  const [deliveryHours, setDeliveryHours] = useState([]);
  const [isShipping, setIsShipping] = useState(true);
  const [values, setValues] = useState({
    email: userData.email,
    address: userData.address,
    deliveryTime: 1,
    paymentMenthod: null,
  });
  useEffect(() => {
    getStoreHours();
    getDeliveryHours();
    window.scroll(0, 0);
  }, []);

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
  };

  const handleConfirmPurchase = async (e) => {
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
    console.log(purchaseData);
    const resp = await fetchApi("sales.php", "POST", purchaseData);
    console.log(resp);
    if (resp.status === "successfully") {
      console.log("successfully");
      setCart([]);
      navigate("/");
      return Swal.fire({
        icon: "success",
        text: "Compra concretada!",
        timer: 1500,
        showConfirmButton: true,
        confirmButtonColor: "#f5990ff3",
      });
    } else {
      return Swal.fire({
        icon: "error",
        text: "Error al concretar la compra",
        timer: 1500,
        showConfirmButton: true,
        confirmButtonColor: "#f5990ff3",
      });
    }
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
  console.log(!userData.auth);
  return (
    <ContainerBase>
      <div className="form-container">
        <img className="form-img" src={Purchase} alt="Imagen" />
        <form>
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
            </div>
            <Input
              name="address"
              id="address"
              value={values.address}
              placeholder="Ingrese direccion..."
              onChange={(e) => setAddress(e.target.value)}
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
              cart === [] ||
              values.address === "" ||
              values.deliveryTime === null ||
              values.paymentMenthod === null
            }
          >
            Confirmar
          </button>
        </form>
      </div>
    </ContainerBase>
  );
};

export default BuyForm;
