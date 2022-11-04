/** @format */

import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import React, { useState } from "react";
import { useEffect } from "react";
import {
  faArrowRightLong,
  faCheck,
  faTruckFast,
  faHouseCircleCheck,
  faHourglass,
  faLocationDot,
  faXmark,
  faTruck,
} from "@fortawesome/free-solid-svg-icons";
import { fetchApi } from "../../API/api";

const SaleStatusHistory = ({ saleId, status }) => {
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
        width: "220px",
        margin: "10px",
      }}
    >
      <FontAwesomeIcon
        icon={faHourglass}
        size={"lg"}
        color={status === "PENDIENTE" ? "orange" : "black"}
      />
      <FontAwesomeIcon icon={faArrowRightLong} size={"sm"} />
      <FontAwesomeIcon
        icon={faCheck}
        size={"lg"}
        color={status === "CONFIRMADO" ? "orange" : "black"}
      />
      <FontAwesomeIcon icon={faArrowRightLong} size={"sm"} />
      {status !== "PICK UP" ? (
        <FontAwesomeIcon
          icon={faTruckFast}
          size={"lg"}
          color={status === "EN VIAJE" ? "orange" : "black"}
        />
      ) : (
        <FontAwesomeIcon icon={faLocationDot} size={"lg"} color={"orange"} />
      )}
      <FontAwesomeIcon icon={faArrowRightLong} size={"sm"} />
      {status !== "CANCELADA" ? (
        <FontAwesomeIcon
          icon={faHouseCircleCheck}
          size={"lg"}
          color={status === "ENTREGADO" ? "orange" : "black"}
        />
      ) : (
        <FontAwesomeIcon icon={faXmark} size={"lg"} color={"red"} />
      )}
    </div>
  );
};

export default SaleStatusHistory;
