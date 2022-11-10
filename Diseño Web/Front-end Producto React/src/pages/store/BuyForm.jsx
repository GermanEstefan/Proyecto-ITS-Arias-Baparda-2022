/** @format */

import React, { useEffect, useState, useContext } from "react";
import { fetchApi } from "../../API/api";
import Swal from "sweetalert2";
import Input from "../../components/store/Input";
import Purchase from "../../assets/img/purchase.jpg";
import loading from "../../assets/img/loading.gif";
import Select from "react-select";
import { cartContext, userStatusContext } from "../../App";
import ContainerBase from "../../components/store/ContainerBase";
import { useNavigate, Link } from "react-router-dom";


const paymentMethods = [
  { value: 0, label: "Efectivo" },
  { value: 1, label: "Online" },
];

const BuyForm = () => {
  const { cart, setCart } = useContext(cartContext);
  const navigate = useNavigate();
  const { userData } = useContext(userStatusContext);
  const [isAddressDisable, setIsAddressDisable] = useState(false);
  const [deliveryHours, setDeliveryHours] = useState([]);
  const [hasAddress, setHasAddress] = useState(false);
  const [isLoading, setIsLoading] = useState(false);
  const [values, setValues] = useState({
    email: userData.email,
    address: userData.address,
    deliveryTime: 1,
    paymentMenthod: null,
  });

  useEffect(() => {
    getDeliveryHours();
    setHasAddress(userData.address === null);
    console.log(userData.address !== null);

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
    }
    if (value === "Dirección alternativa") {
      setIsAddressDisable(false);
      setAddress("");
    }
  };

  const handleConfirmPurchase = async (e) => {
    setIsLoading(true);
    e.preventDefault();
    if (!isLoading) {
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
        setIsLoading(true);
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
        setIsLoading(false);
        return Swal.fire({
          icon: "error",
          text: resp.result.error_msg,
          timer: 1500,
          showConfirmButton: true,
          confirmButtonColor: "#f5990ff3",
        });
      }
    }
  };

  const getDeliveryHours = async () => {
    const resp = await fetchApi("deliverys.php?delivery", "GET");
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
                  defaultChecked={hasAddress}
                  value={"Dirección actual"}
                  name="addressRadio"
                  disabled={hasAddress}
                />{" "}
                Dirección actual
              </label>
              {hasAddress && (
                <i style={{ fontSize: "small", marginLeft: "15px" }}>
                  No tienes una dirección asignada
                </i>
              )}
              <label>
                <input
                  type="radio"
                  defaultChecked={!hasAddress}
                  value={"Dirección alternativa"}
                  name="addressRadio"
                />{" "}
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
          <span className="mt-5">{"Horarios de envío"}</span>
          <div className="selectSection">
            <Select
              name="deliveryTime"
              id="deliveryTime"
              className="select"
              onChange={(e) => setDeliveryTime(e.value)}
              options={deliveryHours}
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
          </div>

          {isLoading && <img src={loading} style={{ width: "200px", margin: "auto" }} />}
          <button
            className="submit-button"
            onClick={(e) => handleConfirmPurchase(e)}
            style={{ width: "65%" }}
            type="submit"
            disabled={isLoading}
          >
            Confirmar
          </button>
          <Link to="/shoppingCart">Volver al carrito</Link>
        </form>
      </div>
    </ContainerBase>
  );
};

export default BuyForm;
