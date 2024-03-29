/** @format */

import React, { useState, useEffect } from "react";
import { Collapse } from "react-collapse";
import { fetchApi } from "../../API/api";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faCaretDown, faCaretUp } from "@fortawesome/free-solid-svg-icons";
import Swal from "sweetalert2";

export const HistoryItem = ({ sale }) => {
  const [toggleCollapse, setToggleCollapse] = useState(false);
  const [saleProducts, setSaleProducts] = useState([]);
  useEffect(() => {
    getSaleProducts();
  }, []);

  const handleClick = () => {
    setToggleCollapse(!toggleCollapse);
  };
  const getSaleProducts = async () => {
    try {
      const resp = await fetchApi(`sales.php?saleDetail=${sale.ID}`, "GET");
      if (resp.status === "successfully") {
        setSaleProducts(resp.result.data.productSale || []);
      }
      
    } catch (error) {
      console.error(error);
      return Swal.fire({
        icon: "error",
        text: "Error 500, servidor caido",
        timer: 3000,
        showConfirmButton: true,
      });
    }
  };
  return (
    <div onClick={handleClick}>
      <div className="historyItem">
        <div>
          <strong>#{sale.ID} </strong>
          <span>{sale.date}</span>
          <strong className="status">{sale.status}</strong>
        </div>
        <div className="priceAndCollapse">
          <span>${sale.status !== "CANCELADA" ? sale.total : "--.--"}</span>
          <FontAwesomeIcon icon={toggleCollapse ? faCaretUp : faCaretDown} />
        </div>
      </div>
      <div className="historyCollapse">
        <Collapse isOpened={toggleCollapse}>
          {saleProducts.map((saleDetail, index) => (
            <div key={index} className="collapseSale">
              <div>
                <span>{saleDetail.productName}</span>
                <span>x{saleDetail.quantity}</span>
              </div>
              <span>${saleDetail.total} </span>
            </div>
          ))}
        </Collapse>
      </div>
    </div>
  );
};
