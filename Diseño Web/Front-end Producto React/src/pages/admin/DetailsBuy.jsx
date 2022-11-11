import React, { useState, useEffect } from "react";
import { useParams } from "react-router-dom";
import { fetchApi } from "../../API/api";
import ContainerBase from "../../components/admin/ContainerBase";

const DetailBuys = () => {
  const { idBuy } = useParams();
  const [detailsBuy, setDetailsBuy] = useState({});
  const {
    infoResponsible,
    infoSupplier,
    details,
    totalSupply,
    idSupply,
  } = detailsBuy;

  useEffect(() => {
    fetchApi(`supply.php?supplyDetail=${idBuy}`, "GET")
      .then((res) => {
        const detailsBuyData = res.result.data;
        
        setDetailsBuy(detailsBuyData);
      })
      .catch((err) => console.error(err));
  }, []);

  return (
    <ContainerBase>
      <section className="container_section detail-buys flex-column-center-xy">
        {infoResponsible &&
        infoSupplier &&
        details &&
        totalSupply &&
        idSupply ? (
          <>
            <h1> {`DETALLE DE COMPRA CON ID: ${idSupply} `}</h1>
            <p>{`Total gastado:$ ${totalSupply}`}</p>

            <div className="detail-buys_info-container">
              <div>
                <h2>Responsable de la compra:</h2>
                <ul>
                  <li>
                    <strong>CI:</strong>
                    <span>{infoResponsible.employeeDoc}</span>
                  </li>
                  <li>
                    <strong>Empleado:</strong>
                    <span>{infoResponsible.employeeName}</span>
                  </li>
                  <li>
                    <strong>Detalle:</strong>
                    <span>{infoResponsible.comment}</span>
                  </li>
                </ul>
              </div>

              <div>
                <h2>Informacion del proveedor</h2>
                <ul>
                  <li>
                    <strong>ID:</strong>
                    <span>{infoSupplier.idSupplier}</span>
                  </li>
                  <li>
                    <strong>Razon Social:</strong>
                    <span>{infoSupplier.name}</span>
                  </li>
                  <li>
                    <strong>RUT:</strong>
                    <span>{infoSupplier.rut}</span>
                  </li>
                </ul>
              </div>

              <div>
                <h2>Lista de productos:</h2>
                {details.map((detail, i) => (
                  <>
                    <h3>{`Producto ${i + 1}`}</h3>
                    <ul>
                      <li>
                        <strong>Codigo de barras:</strong>
                        <span>{detail.barcode}</span>
                      </li>
                      <li>
                        <strong>Nombre del producto:</strong>
                        <span>{detail.nameProduct}</span>
                      </li>
                      <li>
                        <strong>Cantidad de unidades:</strong>
                        <span>{detail.quantity}</span>
                      </li>
                      <li>
                        <strong>Costo unitario: $</strong>
                        <span>{detail.costUnit}</span>
                      </li>
                      <li>
                        <strong>Costo total: $</strong>
                        <span>{detail.costTotal}</span>
                      </li>
                    </ul>
                  </>
                ))}
              </div>
            </div>
          </>
        ) : null}
      </section>
    </ContainerBase>
  );
};

export default DetailBuys;
