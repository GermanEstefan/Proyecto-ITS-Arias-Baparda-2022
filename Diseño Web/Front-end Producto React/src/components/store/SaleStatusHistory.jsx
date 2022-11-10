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
  faXmark
} from "@fortawesome/free-solid-svg-icons";
import { fetchApi } from "../../API/api";

const SaleStatusHistory = ({ saleId }) => {
  const [lastStatus, setLastStatus] = useState("");

  useEffect(() => {
    getStatuslist();
  }, []);

  const getStatuslist = async () => {
    try {
      const resp = await fetchApi(`sales.php?History=${saleId}`, "GET");
      setLastStatus(resp.result.data.history[0].status || "");
    } catch (error) {
      console.error(error);
      alert("ERROR, comunicarse con el administrador");
    }
  };
  return (
    <div className="statusHistory">
      <FontAwesomeIcon
        className="icon"
        icon={faHourglass}
        size={"lg"}
        color={lastStatus === "PENDIENTE" ? "orange" : "black"}
      />
      <FontAwesomeIcon className="icon" icon={faArrowRightLong} size={"sm"} />
      <FontAwesomeIcon
        className="icon"
        icon={faCheck}
        size={"lg"}
        color={lastStatus === "CONFIRMADO" ? "orange" : "black"}
      />
      <FontAwesomeIcon className="icon" icon={faArrowRightLong} size={"sm"} />
      {lastStatus !== "PICK-UP" ? (
        <FontAwesomeIcon
          className="icon"
          icon={faTruckFast}
          size={"lg"}
          color={lastStatus === "EN VIAJE" ? "orange" : "black"}
        />
      ) : (
        <FontAwesomeIcon
          className="icon"
          icon={faLocationDot}
          size={"lg"}
          color={"orange"}
        />
      )}
      <FontAwesomeIcon className="icon" icon={faArrowRightLong} size={"sm"} />
      {lastStatus !== "CANCELADA" ? (
        <FontAwesomeIcon
          className="icon"
          icon={faHouseCircleCheck}
          size={"lg"}
          color={lastStatus === "ENTREGADO" ? "orange" : "black"}
        />
      ) : (
        <FontAwesomeIcon
          className="icon"
          icon={faXmark}
          size={"lg"}
          color={"red"}
        />
      )}
    </div>
  );
};

export default SaleStatusHistory;
