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
          <div>
            <SaleStatusHistory saleId={sale.ID} />
            <HistoryItem key={index} sale={sale} />
          </div>
        ))}
      {sales.length === 0 && (
        <p style={{ marginLeft: "15px" }}>Aún no has realizado ninguna compra</p>
      )}
    </Animated>
  );
};

export default SalesHistory;
