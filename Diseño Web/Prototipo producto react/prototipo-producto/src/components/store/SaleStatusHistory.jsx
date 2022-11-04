/** @format */

import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import React, { useState } from "react";
import { useEffect } from "react";
import { faArrowRightLong, faCheck, faTruckFast, faHouseCircleCheck } from "@fortawesome/free-solid-svg-icons";
import { fetchApi } from "../../API/api";

const SaleStatusHistory = ({ saleId }) => {
  const [lastStatus, setLastStatus] = useState("");

  useEffect(() => {
    getStatuslist();
  }, []);

  const getStatuslist = async () => {
    const resp = await fetchApi(`sales.php?History=${saleId}`, "GET");
    console.log(resp);
    setLastStatus(resp.result.data.history[resp.result.data.history.length - 1].status || "");
  };
  return (
    <div
      style={{
        display: "flex",
        alignItems: "center",
        justifyContent: "space-around",
        width: "200px",
        margin: "10px",
      }}
    >
      <FontAwesomeIcon
        icon={faCheck}
        size={"2x"}
        color={lastStatus === "CONFIRMADO" ? "orange" : "black"}
      />
      <FontAwesomeIcon icon={faArrowRightLong} size={"1x"} />
      <FontAwesomeIcon
        icon={faTruckFast}
        size={"2x"}
        color={lastStatus === "EN VIAJE" ? "orange" : "black"}
      />
      <FontAwesomeIcon icon={faArrowRightLong} size={"1x"} />
      <FontAwesomeIcon
        icon={faHouseCircleCheck}
        size={"2x"}
        color={lastStatus === "ENTREGADO" ? "orange" : "black"}
      />
      
    </div>
  );
};

export default SaleStatusHistory;
