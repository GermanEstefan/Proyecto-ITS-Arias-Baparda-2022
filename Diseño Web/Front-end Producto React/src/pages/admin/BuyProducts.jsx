import { faPlusCircle, faXmark } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import React, { useContext, useEffect } from "react";
import { useState } from "react";
import { fetchApi } from "../../API/api";
import { userStatusContext } from "../../App";
import ContainerBase from "../../components/admin/ContainerBase";
import { useForm } from "../../hooks/useForm";

const BuyProducts = () => {
  const { ci } = useContext(userStatusContext).userData;
  const [suppliers, setSuppliers] = useState([]);
  const [products, setProducts] = useState([]);
  const initStateBuyProductsGeneralValues = {
    idSupplier: "",
    comment: "",
    products: [],
  };
  const [buyProductsGeneralValues, handleValuesChange, resetValues] = useForm(
    initStateBuyProductsGeneralValues
  );
  const { supplier_id, comment } = buyProductsGeneralValues;

  const initStateLoading = {
    showMessage: false,
    message: "",
    error: false,
  };
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(initStateLoading);

  useEffect(() => {
    const supplierPromise = fetchApi("suppliers.php?active", "GET");
    const productsPromise = fetchApi("products.php?BOProducts", "GET");
    Promise.all([supplierPromise, productsPromise]).then(
      ([suppliers, productss]) => {
        setSuppliers(suppliers.result.data);
        console.log(productss);
        setProducts(productss.result.data);
      }
    );
  }, []);

  const initStateProductsToBuy = [{ barcode: "", quantity: "", cost_unit: "" }];
  const [productsToBuy, setProductsToBuy] = useState(initStateProductsToBuy);
  const handleAddNewProductToBuy = () =>
    setProductsToBuy([
      ...productsToBuy,
      { barcode: "", quantity: "", cost_unit: "" },
    ]);
  const handleDeleteProductToBuy = (i) => {
    const arrayCopy = [...productsToBuy];
    arrayCopy.splice(i, 1);
    setProductsToBuy(arrayCopy);
  };
  const handleChangeProductToBuy = ({ target }, i) => {
    const arrayModificado = [...productsToBuy];
    productsToBuy[i][target.name] = target.value;
    setProductsToBuy(arrayModificado);
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    const bodyOfRequest = {
      ...buyProductsGeneralValues,
      products: productsToBuy,
      employee_ci: ci,
    };
    setLoading(true);
    try {
      const resp = await fetchApi("supply.php", "POST", bodyOfRequest);
      console.log(bodyOfRequest);
      console.log(resp);
      if (resp.status === "error") {
        setError({
          showMessage: true,
          message: resp.result.error_msg,
          error: true,
        });
        return setTimeout(() => setError(initStateLoading), 3000);
      }
      setError({ showMessage: true, message: resp.result.msg, error: false });
      resetValues();
      setProductsToBuy(initStateProductsToBuy);
      return setTimeout(() => setError(initStateLoading), 3000);
    } catch (error) {
      console.error(error);
    } finally {
      setLoading(false);
    }
  };

  return (
    <ContainerBase>
      <section className="container_section buy-products flex-column-center-xy">
        <h1>Compra de productos</h1>
        <h2>Completa el formulario para registrar una compra</h2>

        <form onSubmit={handleSubmit} className="flex-column-center-xy">
          <div className="supplier-line">
            <label className="sel-supplier">Seleccione proveedor</label>
            <select
              className="select-form"
              value={supplier_id}
              onChange={handleValuesChange}
              name="idSupplier"
            >
              <option value="" selected disabled>
                Seleccione Proveedor
              </option>
              {suppliers.map(({ id_supplier, company_name }) => (
                <option required value={id_supplier} key={id_supplier}>
                  {company_name}
                </option>
              ))}
            </select>
          </div>

          <div className="buy-product-line">
            <FontAwesomeIcon
              className="add-line-buy-product"
              icon={faPlusCircle}
              onClick={handleAddNewProductToBuy}
            />
            {productsToBuy.map((product, i) => (
              <div key={i} className="buy-product-line_item">
                <div>
                  <label htmlFor="" className="label-form">
                    Lista de productos
                  </label>
                  <select
                    name="barcode"
                    required
                    value={product.barcode}
                    onChange={(e) => handleChangeProductToBuy(e, i)}
                    className="select-form"
                  >
                    <option value="" selected disabled>
                      Seleccione
                    </option>
                    {products.map((product) => (
                      <option key={product.barcode} value={product.barcode}>
                        {" "}
                        {`${product.name} - ${product.design} - ${product.size}`}{" "}
                      </option>
                    ))}
                  </select>
                </div>

                <div>
                  <label htmlFor="" className="label-form">
                    Unidades
                  </label>
                  <input
                    type="number"
                    min="1"
                    className="input-form"
                    name="quantity"
                    required
                    value={product.quantity}
                    onChange={(e) => handleChangeProductToBuy(e, i)}
                  />
                </div>

                <div>
                  <label htmlFor="" className="label-form">
                    Costo unitario
                  </label>
                  <input
                    type="number"
                    min="0"
                    className="input-form"
                    name="cost_unit"
                    required
                    value={product.costo}
                    onChange={(e) => handleChangeProductToBuy(e, i)}
                  />
                </div>
                {i !== 0 && (
                  <FontAwesomeIcon
                    onClick={() => handleDeleteProductToBuy(i)}
                    icon={faXmark}
                    className="remove-line-buy-product"
                  />
                )}
              </div>
            ))}
          </div>

          <div className="txtarea-buy-product">
            <label htmlFor="">Comentario</label>
            <textarea
              className="textarea-form"
              name="comment"
              value={comment}
              onChange={handleValuesChange}
            ></textarea>
          </div>

          <button
            className={`button-form ${loading && "opacity"}`}
            disabled={loading}
            type="submit"
          >
            {loading ? "CARGANDO..." : "REGISTRAR COMPRA"}
          </button>
          <br />
          {error.showMessage && (
            <span
              className={`${
                error.error ? "warning-message" : "successfully-message"
              } `}
            >
              {error.message}
            </span>
          )}
        </form>
      </section>
    </ContainerBase>
  );
};

export default BuyProducts;
