import React, { useContext, useState } from "react";
import { useEffect } from "react";
import { fetchApi } from "../../API/api";
import { userStatusContext } from "../../App";
import { HistoryItem } from "./HistoryItem";

const SalesHistory = () => {
  const { userData } = useContext(userStatusContext);
  const [sales, setSales] = useState([]);
  useEffect(() => {
    getSalesHistory();
  }, []);

  const getSalesHistory = async () => {
    const resp = await fetchApi(
      `sales.php?salesClient=${userData.email}`,
      "GET"
    );
    resp && setSales(resp.result.data.sales);
  };

  return (
    <>
      <h1 style={{marginLeft: '15px'}}>Historial de compras</h1>
      {sales.map((sale, index) => (
        <HistoryItem key={index} sale={sale} />
      ))}
    </>
  );
};

export default SalesHistory;
