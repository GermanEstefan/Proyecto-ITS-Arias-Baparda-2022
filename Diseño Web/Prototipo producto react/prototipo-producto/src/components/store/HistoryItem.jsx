/** @format */

import React, { useState, useEffect } from "react";
import { Collapse } from "react-collapse";
import { fetchApi } from "../../API/api";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faCaretDown, faCaretUp } from "@fortawesome/free-solid-svg-icons";

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
    const resp = await fetchApi(`sales.php?saleDetail=${sale.ID}`, "GET");
    console.log(resp);
    if (resp.status === "successfully") {
      setSaleProducts(resp.result.data.details || []);
    }
  };
  return (
    <div onClick={handleClick}>
      <div className="historyItem">
        <div>
          <span>{sale.date}</span>
          <strong>{sale.status}</strong>
        </div>
        <div>
          <span>{sale.total}$</span>
          <FontAwesomeIcon icon={toggleCollapse ? faCaretUp : faCaretDown} />
        </div>
      </div>
      <div className="historyCollapse">
        <Collapse isOpened={toggleCollapse}>
          {saleProducts.map((saleDetail) => (
            <div className="collapseSale">
              <div>
                <span>{saleDetail.product}</span>
                <span>{saleDetail.quantity > 1 && `x${saleDetail.quantity}`}</span>
              </div>
              <span>{saleDetail.total} $</span>
            </div>
          ))}
        </Collapse>
      </div>
    </div>
  );
};
