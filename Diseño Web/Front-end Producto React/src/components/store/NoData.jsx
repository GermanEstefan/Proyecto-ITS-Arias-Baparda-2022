/** @format */

import React from "react";
import emptybox from "../../assets/img/emptybox.png";

const NoData = ({ message }) => {
  return (
    <div className="noDataContainer">
      <img src={emptybox} alt=":(" />
      <h3>{message}</h3>
    </div>
  );
};

export default NoData;
