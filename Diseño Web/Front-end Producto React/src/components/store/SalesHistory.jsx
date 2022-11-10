/** @format */

import React, { useContext, useState } from "react";
import { useEffect } from "react";
import { fetchApi } from "../../API/api";
import { userStatusContext } from "../../App";
import { HistoryItem } from "./HistoryItem";
import { Animated } from "react-animated-css";
import SaleStatusHistory from "./SaleStatusHistory";
import NoData from "./NoData";

const SalesHistory = () => {
  const { userData } = useContext(userStatusContext);
  const [sales, setSales] = useState([]);
  useEffect(() => {
    getSalesHistory();
    window.scroll(0, 0);
  }, []);

  const getSalesHistory = async () => {
    try {
      const resp = await fetchApi(
        `sales.php?salesClient=${userData.email}`,
        "GET"
      );

      if (resp.status === "error") {
        setSales([]);
      }
      if (resp.status === "successfully") {
        setSales(resp.result.data.sales || []);
      }
    } catch (error) {
      console.error(error);
      alert("ERROR, comunicarse con el administrador");
    }
  };

  return (
    <Animated
      animationIn="fadeIn"
      animationOut="fadeOutRight"
      animationInDuration={500}
      isVisible={true}
    >
      <div>
        <h1 style={{ marginLeft: "15px" }}>Historial de compras</h1>
        {sales.length > 0 &&
          sales.map((sale, index) => (
            <div className="historyContainer">
              <HistoryItem key={index} sale={sale} />
              <SaleStatusHistory saleId={sale.ID} />
            </div>
          ))}
        {sales.length === 0 && (
          <NoData message={"Aún no has realizado ninguna compra"} />
        )}
      </div>
    </Animated>
  );
};

export default SalesHistory;
