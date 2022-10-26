import React, { useContext } from "react";
import { useEffect } from "react";
import { fetchApi } from "../../API/api";
import { userStatusContext } from "../../App";

const SalesHistory = () => {
  const { userData } = useContext(userStatusContext);
  useEffect(() => {
    getSalesHistory();
  }, []);

  const getSalesHistory = async () => {
    console.log(`salesClient=${userData.email}`);
    const resp = await fetchApi(`salesClient=${userData.email}`, "GET");
    console.log(resp)
  };
  return <h1>Historial de compras</h1>;
};

export default SalesHistory;
