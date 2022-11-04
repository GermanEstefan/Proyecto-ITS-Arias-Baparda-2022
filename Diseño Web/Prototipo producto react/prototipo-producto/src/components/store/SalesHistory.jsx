/** @format */

import React, { useContext, useState } from "react";
import { useEffect } from "react";
import { fetchApi } from "../../API/api";
import { userStatusContext } from "../../App";
import { HistoryItem } from "./HistoryItem";
import { Animated } from "react-animated-css";
import SaleStatusHistory from "./SaleStatusHistory";

const SalesHistory = () => {
  const { userData } = useContext(userStatusContext);
  const [sales, setSales] = useState([]);
  useEffect(() => {
    getSalesHistory();
    window.scroll(0, 0);
  }, []);

  const getSalesHistory = async () => {
    const resp = await fetchApi(`sales.php?salesClient=${userData.email}`, "GET");

    if (resp.status === "error") {
      setSales([]);
    }
    if (resp.status === "successfully") {
      setSales(resp.result.data.sales || []);
    }
  };

  const statusMock = [
    "PENDIENTE",
    "CONFIRMADO",
    "EN VIAJE",
    "PICK UP",
    "ENTREGADO",
    "CANCELADA",
    "EN VIAJE",
    "PICK UP",
    "ENTREGADO",
  ];

  return (
    <Animated
      animationIn="fadeIn"
      animationOut="fadeOutRight"
      animationInDuration={500}
      isVisible={true}
    >
      <h1 style={{ marginLeft: "15px" }}>Historial de compras</h1>
      {sales.length > 0 &&
        sales.map((sale, index) => (
          <div style={{marginTop: '20px ', borderBottom: '1px solid black', width: '630px'}}>
            <SaleStatusHistory saleId={sale.ID} status={statusMock[index]}/>
            <HistoryItem key={index} sale={sale} status={statusMock[index]}/>
          </div>
        ))}
      {sales.length === 0 && (
        <p style={{ marginLeft: "15px" }}>Aún no has realizado ninguna compra</p>
      )}
    </Animated>
  );
};

export default SalesHistory;
