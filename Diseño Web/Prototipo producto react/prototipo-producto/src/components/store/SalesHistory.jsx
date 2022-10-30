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
    if(resp.result.error_msg === 'Aun no compro nada'){
      setSales([])
    }
    if(resp.status === 'successfully'){
      setSales(resp.result.data)
    }
  };

  return (
    <>
      <h1 style={{marginLeft: '15px'}}>Historial de compras</h1>
      {sales.map((sale, index) => (
        <HistoryItem key={index} sale={sale} />
      ))}
      {
        sales.length === 0 && <p style={{marginLeft: '15px'}}>Aún no has realizado  ninguna compra</p>
      }
    </>
  );
};

export default SalesHistory;
