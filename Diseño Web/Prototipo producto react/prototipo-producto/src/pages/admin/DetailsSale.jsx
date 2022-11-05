import React, { useState, useEffect, useContext, Fragment } from "react";
import { useParams } from "react-router-dom";
import Swal from "sweetalert2";
import { fetchApi } from "../../API/api";
import { userStatusContext } from "../../App";
import ContainerBase from "../../components/admin/ContainerBase";

const DetailsSales = () => {
  const { idSale } = useParams();
  const { ci } = useContext(userStatusContext).userData;
  const [saleDetail, setSaleDetail] = useState({});
  const {
    statusSale,
    totalSale,
    clientInfo,
    saleInfo,
    productSale,
  } = saleDetail;
  const [states, setStates] = useState([]);
  const [historySale, setHistorySale] = useState([]);

  useEffect(() => {
    const statesPromise = fetchApi("status.php", "GET");
    const saleDetailPromise = fetchApi(`sales.php?saleDetail=${idSale}`, "GET");
    Promise.all([statesPromise, saleDetailPromise])
      .then(([states, saleDetail]) => {
        const statesData = states.result.data;
        const saleData = saleDetail.result.data;
        setStates(statesData);
        setSaleDetail(saleData);
        console.log(saleData);
      })
      .catch((err) => console.error(err));
  }, []);

  const handleGetHistoryOfSale = async () => {
    try {
      const resp = await fetchApi(`sales.php?reportHistory=${idSale}`, "GET");
      console.log(resp);
      setHistorySale(resp.result.data.history);
    } catch (error) {
      console.error(error);
    }
  };

  const handleChangeState = async (e, newStateName) => {
    e.preventDefault();
    const newState = e.target[0].value;
    const { name } = states.find((state) => state.id_status === newState);
    const bodyOfRequest = {
      status: newState,
      employeeDoc: ci,
    };
    const resp = await fetchApi(
      `sales.php?actualizeSale=${idSale}`,
      "PATCH",
      bodyOfRequest
    );
    if (resp.status === "error") {
      setSaleDetail({ ...saleDetail, statusSale: newState });
      return Swal.fire({
        icon: "error",
        text: resp.result.error_msg,
        timer: 2000,
        showConfirmButton: false,
      });
    }
    setSaleDetail({ ...saleDetail, statusSale: name });
    return Swal.fire({
      icon: "success",
      text: "Estado de venta cambiado con exito",
      timer: 2000,
      showConfirmButton: false,
    });
  };

  return (
    <ContainerBase>
      <section className="container_section sale-detail flex-column-center-xy">
        <div className="sale-detail-content">
          <div className="sale-detail-content_info-card">
            <span>
              Estado actual: <strong> {statusSale} </strong>
            </span>
            <div className="sale-detail-content_info-card_section">
              <strong>Info venta</strong>
              <ul>
                <li>
                  <strong>ID:</strong> {idSale}
                </li>
                <li>
                  <strong>Total:</strong> {totalSale}
                </li>
                <li>
                  <strong>Forma de pago:</strong>
                  {saleInfo &&
                    (saleInfo.payment === "0" ? "Efectivo" : "Online")}
                </li>
                <li>
                  <strong>Horario de entrega:</strong>
                  {saleInfo && saleInfo.deliverySale}
                </li>
                <li>
                  <strong>Direccion:</strong> {saleInfo && saleInfo.addressSale}
                </li>
              </ul>
            </div>
            <div className="sale-detail-content_info-card_section">
              <strong>Info cliente</strong>
              <ul>
                <li>
                  <strong>Email:</strong> {clientInfo && clientInfo.clientMail}
                </li>
                <li>
                  <strong>Nombre:</strong>
                  {clientInfo &&
                    (clientInfo.companyName
                      ? clientInfo.companyName
                      : clientInfo.clientName)}
                </li>
              </ul>
            </div>
            <div className="sale-detail-content_info-card_section">
              <strong>Productos comprados</strong>
              {productSale &&
                productSale.map((product, i) => (
                  <div key={product.barcode}>
                    <span>Producto {i + 1}</span>
                    <ul>
                      <li>
                        <strong>Codigo de barras:</strong>
                        <span>{product.barcode}</span>
                      </li>
                      <li>
                        <strong>Nombre: </strong>
                        <span> {product.productName} </span>
                      </li>
                      <li>
                        <strong>Cantidad:</strong>
                        <span>{product.quantity}</span>
                      </li>
                      <li>
                        <strong>Total</strong>
                        <span>{product.total}</span>
                      </li>
                    </ul>
                  </div>
                ))}
            </div>
          </div>

          <div className="sale-detail_controls">
            <strong>Cambiar estado de venta:</strong>
            <form onSubmit={handleChangeState}>
              <select className="select-form" required>
                <option value="" selected disabled>
                  Seleccione un estado
                </option>
                {states.map(
                  (state) =>
                    state.id_status !== "1" && (
                      <option key={state.id_status} value={state.id_status}>
                        {state.name}
                      </option>
                    )
                )}
              </select>
              <button
                className={
                  statusSale === "CANCELADA"
                    ? "button-form opacity"
                    : "button-form"
                }
                disabled={statusSale === "CANCELADA" ? true : false}
              >
                Cambiar estado
              </button>
            </form>
            {statusSale === "CANCELADA" && (
              <strong className="bija">
                No se puede cambiar de estado una venta que fue cancelada
              </strong>
            )}
            <hr />
            <button onClick={handleGetHistoryOfSale}>
              Ver historial de venta
            </button>
            {historySale.length > 0 &&
              historySale.map((sale) => (
                <Fragment key={sale.regNr}>
                  <ul>
                    <li>
                      <strong>Responsable del cambio de estado:</strong>
                      <span>{sale.employeeName}</span>
                    </li>
                    <li>
                      <strong>Fecha del cambio:</strong>
                      <span> {sale.date} </span>
                    </li>
                    <li>
                      <strong>Estado al que se cambio:</strong>
                      <span>{sale.status}</span>
                    </li>
                    <li>
                      <strong>Comentario:</strong>
                      <span>{sale.comment} </span>
                    </li>
                  </ul>
                  <hr />
                </Fragment>
              ))}
          </div>
        </div>
      </section>
    </ContainerBase>
  );
};

export default DetailsSales;
