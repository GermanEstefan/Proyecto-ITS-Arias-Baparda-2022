import { faCheck, faPencil, faTrash } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import React, { Fragment, useEffect } from "react";
import { useState } from "react";
import { useNavigate } from "react-router-dom";
import Swal from "sweetalert2";
import { fetchApi } from "../../API/api";
import ContainerBase from "../../components/admin/ContainerBase";

const ListProducts = () => {
  const navigate = useNavigate();
  const [products, setProducts] = useState([]);
  const [modelsOfProduct, setModelsOfProduct] = useState({
    idProduct: null,
    models: [],
  });

  const [promos, setPromos] = useState([]);
  const [productOfPromos, setProductOfPromos] = useState({
    idPromo: null,
    productsPromo: [],
  });

  const [loadingFlags, setLoadingFlags] = useState({ fetchingUsers: true });

  useEffect(() => {
    const promoPromise = fetchApi("products.php?BOPromos", "GET");
    const productsPromise = fetchApi("products.php?BOonlyProducts", "GET");
    Promise.all([productsPromise, promoPromise])
      .then(([products, promos]) => {
        
        setProducts(products.result.data);
        setPromos(promos.result.data);
      })
      .catch((err) => {
        alert("Error interno");
        console.log(err);
      })
      .finally(() => setLoadingFlags({ fetchingUsers: false }));
  }, [modelsOfProduct]);

  const handleGetModelsOfProduct = async (idProduct) => {
    const resp = await fetchApi(`products.php?BOmodelsOfProduct=${idProduct}`);
    
    setModelsOfProduct({ idProduct, models: resp.result.data.models });
  };

  const handleDisableModel = async (barcode, i) => {
    try {
      const resp = await fetchApi(
        `products.php?barcode=${barcode}&actionMin=disable`,
        "PATCH"
      );
      
      if (resp.status === "successfully") {
        const modelsMapped = modelsOfProduct.models.map((model) => {
          if (model.barcode === barcode) {
            model.state = 0;
          }
          return model;
        });
        setModelsOfProduct(modelsMapped);
        Swal.fire({
          icon: "success",
          text: "Producto desabilitado con exito",
          timer: 2000,
          showConfirmButton: false,
        });
      }
    } catch (error) {
      console.error(error);
    }
  };

  const handleEnableModel = async (barcode, i) => {
    try {
      const resp = await fetchApi(
        `products.php?barcode=${barcode}&actionMin=active`,
        "PATCH"
      );
      
      if (resp.status === "successfully") {
        const modelsMapped = modelsOfProduct.models.map((model) => {
          if (model.barcode === barcode) {
            model.state = 1;
          }
          return model;
        });
        setModelsOfProduct(modelsMapped);
        Swal.fire({
          icon: "success",
          text: "Producto habilitado con exito",
          timer: 2000,
          showConfirmButton: false,
        });
      }
    } catch (error) {
      console.error(error);
    }
  };

  const handleGetProductsOfPromo = async (idPromo) => {
    const resp = await fetchApi(`products.php?BOproductsOfPromo=${idPromo}`);
    
    setProductOfPromos({ idPromo, productsPromo: resp.result.data.products });
  };

  return (
    <ContainerBase>
      <section className="container_section list-products">
        {loadingFlags.fetchingUsers ? (
          <span className="fetching-data-message">
            Obteniendo productos y promos ...
          </span>
        ) : (
          <>
            <h1 className="title-page">Productos y promociones</h1>

            <table className="table-template">
              <caption>Productos</caption>
              <tbody>
                <tr>
                  <th>ID PRODUCTO</th>
                  <th>Nombre</th>
                  <th>Descripcion</th>
                  <th>Categoria</th>
                  <th>Precio</th>
                  <th>Controles</th>
                </tr>
                {products.map((product) => (
                  <Fragment key={product.id_product}>
                    <tr
                      className="row-selectable"
                      onClick={() =>
                        handleGetModelsOfProduct(product.id_product)
                      }
                    >
                      <td>{product.id_product}</td>
                      <td>{product.name}</td>
                      <td>{product.description}</td>
                      <td>{product.categoryName}</td>
                      <td>${product.price}</td>
                      <td
                        className="controls-table"
                        onClick={() =>
                          navigate(
                            `/admin/products/edit-product/${product.id_product}`
                          )
                        }
                      >
                        <FontAwesomeIcon icon={faPencil} />
                      </td>
                    </tr>
                    {modelsOfProduct.idProduct === product.id_product && (
                      <table className="child-table">
                        <tbody>
                          <tr className="child-table_row-header">
                            <th>Codigo de barras</th>
                            <th>Talle</th>
                            <th>Diseño</th>
                            <th>Stock</th>
                            <th>Estado</th>
                            <th colSpan={2}>Controles</th>
                          </tr>
                          {modelsOfProduct.models.map((model) => (
                            <tr key={model.barcode} className="child-table_row">
                              <td>{model.barcode}</td>
                              <td>{model.size}</td>
                              <td>{model.design}</td>
                              <td>{model.stock} </td>
                              <td>{model.state}</td>
                              {model.state === "1" ? (
                                <td
                                  className="controls-row-child warning-control"
                                  onClick={() =>
                                    handleDisableModel(model.barcode)
                                  }
                                >
                                  <FontAwesomeIcon icon={faTrash} />
                                </td>
                              ) : (
                                <td
                                  className="controls-row-child successfully-control"
                                  onClick={() =>
                                    handleEnableModel(model.barcode)
                                  }
                                >
                                  <FontAwesomeIcon icon={faCheck} />
                                </td>
                              )}
                              <td
                                className="controls-row-child"
                                onClick={() =>
                                  navigate(
                                    `/admin/products/edit-model/${model.barcode}`
                                  )
                                }
                              >
                                <FontAwesomeIcon icon={faPencil} />
                              </td>
                            </tr>
                          ))}
                        </tbody>
                      </table>
                    )}
                  </Fragment>
                ))}
              </tbody>
            </table>

            <table className="table-template">
              <caption>Promociones</caption>
              <tbody>
                <tr>
                  <th>ID PROMO</th>
                  <th>Nombre</th>
                  <th>Descripcion</th>
                  <th>Precio</th>
                  <th>Stock</th>
                  <th>Estado</th>
                  <th colSpan={2}>Controles</th>
                </tr>
                {promos.map((promo) => (
                  <Fragment key={promo.id_product}>
                    <tr
                      className="row-selectable"
                      onClick={() => handleGetProductsOfPromo(promo.id_product)}
                    >
                      <td>{promo.id_product}</td>
                      <td>{promo.name}</td>
                      <td>{promo.description}</td>
                      <td>${promo.price}</td>
                      <td>{promo.stock}</td>
                      <td>{promo.state}</td>
                      <td
                        className="controls-table"
                        onClick={() =>
                          navigate(
                            `/admin/products/edit-promo/${promo.id_product}`
                          )
                        }
                      >
                        <FontAwesomeIcon icon={faPencil} />
                      </td>
                      <td
                        className="controls-table"
                        onClick={() => alert("eliminar producto")}
                      >
                        <FontAwesomeIcon icon={faTrash} />
                      </td>
                    </tr>
                    {productOfPromos.idPromo === promo.id_product && (
                      <table className="child-table">
                        <tbody>
                          <tr className="child-table_row-header">
                            <th>Codigo de Producto</th>
                            <th>Nombre</th>
                            <th>Cantidad</th>
                            <th>Diseño</th>
                            <th>Talle</th>
                          </tr>
                          {productOfPromos.productsPromo.map((promo) => (
                            <tr
                              key={promo.barcodeProd}
                              className="child-table_row"
                            >
                              <td>{promo.barcodeProd}</td>
                              <td>{promo.name}</td>
                              <td>{promo.quantity}</td>
                              <td>{promo.design} </td>
                              <td>{promo.size}</td>
                            </tr>
                          ))}
                        </tbody>
                      </table>
                    )}
                  </Fragment>
                ))}
              </tbody>
            </table>
          </>
        )}
      </section>
    </ContainerBase>
  );
};

export default ListProducts;
