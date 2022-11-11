/** @format */

import React, { useEffect } from "react";
import { useState } from "react";
import { fetchApi } from "../../API/api";
import ContainerBase from "../../components/admin/ContainerBase";

const Queries = () => {
  const [balanceData, setBalanceData] = useState({});
  const [querieData, setQuerieData] = useState({
    queryToShow: null,
    queryInfo: [],
  });

  useEffect(() => {
    const balanceDataPromise = fetchApi("managements.php?balance", "GET");
    const queryDataPromise = fetchApi("managements.php?bestClients=20", "GET");
    Promise.all([balanceDataPromise, queryDataPromise])
      .then(([balanceData, queryData]) => {
        const balanceInfo = balanceData.result.data[0];
        const queryInfo = queryData.result.data.clients;

        setBalanceData(balanceInfo);
        setQuerieData({ queryToShow: "bestClients", queryInfo });
      })
      .catch((err) => console.error(err));
  }, []);

  const handleChangeQuery = async (e) => {
    const query = e.target.value;
    try {
      if (query === "bestClients") {
        const queryDataPromise = await fetchApi(
          "managements.php?bestClients=20",
          "GET"
        );
        const data = queryDataPromise.result.data.clients;
        setQuerieData({ queryToShow: "bestClients", queryInfo: data });
      } else {
        const queryDataPromise = await fetchApi(
          "managements.php?bestProducts=20",
          "GET"
        );
        const data = queryDataPromise.result.data;

        setQuerieData({ queryToShow: "bestProducts", queryInfo: data });
      }
    } catch (error) {
      console.error(error);
    }
  };

  const handleApplyLimit = async (e, typeQuery) => {
    e.preventDefault();
    const amount = e.target[0].value;
    try {
      if (typeQuery === "bestProducts") {
        const queryData = await fetchApi(
          `managements.php?bestProducts=${amount}`,
          "GET"
        );
        const data = queryData.result.data;
        setQuerieData({ queryToShow: "bestProducts", queryInfo: data });
      } else {
        const queryData = await fetchApi(
          `managements.php?bestClients=${amount}`,
          "GET"
        );
        const data = queryData.result.data.clients;
        setQuerieData({ queryToShow: "bestClients", queryInfo: data });
      }
      e.target[0].value = "";
    } catch (error) {
      console.error(error);
    }
  };

  return (
    <ContainerBase>
      <section className="container_section queries flex-column-center-xy">
        <div className="queries_balance flex-column-center-xy">
          <h3 className="subtitle-queries">BALANCE DE SALDOS</h3>
          <ul>
            <li>
              <strong>TOTAL GANANCIA:</strong>
              <span>${balanceData.TotalSale || 0}</span> |
              <strong>TOTAL INVERTIDO:</strong>
              <span>${balanceData.TotalSupply || 0}</span> |
              <strong>SALDO ACTUAL:</strong>
              <span>${balanceData.Diference || 0}</span>
            </li>
          </ul>
        </div>
        <div className="queries_filters flex-column-center-xy">
          <h2 className="subtitle-queries">
            Consulta:
            <select
              className="select-form"
              name="typeQuery"
              onChange={handleChangeQuery}
            >
              <option value="bestClients" selected>
                Listar clientes con mas compras
              </option>
              <option value="bestProducts">
                Listar productos mas vendidos
              </option>
            </select>
          </h2>
        </div>
        {querieData.queryInfo && querieData.queryToShow === "bestClients" ? (
          <div className="queries_table-container">
            <form onSubmit={(e) => handleApplyLimit(e, "bestClients")}>
              <span>Defina un limite:</span>
              <input type="number" required min={1} />
            </form>

            <table className="table-template">
              <tbody>
                <tr>
                  <th>Correo</th>
                  <th>Nombre Cliente</th>
                  <th>Cantidad de compras</th>
                  <th>Total Facturado</th>
                </tr>
                {querieData.queryInfo.map((infoRow) => (
                  <tr key={infoRow.idClient}>
                    <td>{infoRow.mailClient}</td>
                    <td>{infoRow.clientInfo}</td>
                    <td>{infoRow.totalSales}</td>
                    <td>{infoRow.spentMoney}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        ) : (
          <div className="queries_table-container">
            <form onSubmit={(e) => handleApplyLimit(e, "bestProducts")}>
              <span>Defina un limite:</span>
              <input type="number" required min={1} />
            </form>

            <table className="table-template">
              <tbody>
                <tr>
                  <th>Codigo de barras</th>
                  <th>Nombre Producto</th>
                  <th>Unidades Vendidas</th>
                  <th>Facturado</th>
                </tr>
                {querieData.queryInfo.map((infoRow) => (
                  <tr key={infoRow.barcode}>
                    <td>{infoRow.barcode}</td>
                    <td>{infoRow.product}</td>
                    <td>{infoRow.totalSales}</td>
                    <td>{infoRow.totalRaised}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}
      </section>
    </ContainerBase>
  );
};

export default Queries;
