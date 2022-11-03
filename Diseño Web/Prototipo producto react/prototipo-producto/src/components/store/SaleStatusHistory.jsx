/** @format */

import React, { useState } from "react";
import { useEffect } from "react";
import { fetchApi } from "../../API/api";

const SaleStatusHistory = ({ saleId }) => {
  const [statusList, setStatusList] = useState([]);
  useEffect(() => {
    getStatuslist();
  }, []);

  const getStatuslist = async () => {
    const resp = await fetchApi(`sales.php?History=${saleId}`, "GET");
    console.log(resp.result.data.history);
    setStatusList(resp.result.data.history || []);
  };
  return <div>Historial de estados</div>;
};

export default SaleStatusHistory;
