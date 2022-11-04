/** @format */

import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import React, { useState } from "react";
import { useEffect } from "react";
import { faArrowRightLong, faCheck, faTruckFast, faHouse } from "@fortawesome/free-solid-svg-icons";
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
  return (
    <div style={{display: 'flex', alignItems: 'center', justifyContent:'space-around', width: '200px', margin: '10px'}}>
      <FontAwesomeIcon icon={faCheck} size={"2x"} />
      <FontAwesomeIcon icon={faArrowRightLong} size={"1x"} />
      <FontAwesomeIcon icon={faTruckFast} size={"2x"} color={'orange'}/>
      <FontAwesomeIcon icon={faArrowRightLong} size={"1x"} />
      <FontAwesomeIcon icon={faHouse} size={"2x"} />
    </div>
  );
};

export default SaleStatusHistory;
