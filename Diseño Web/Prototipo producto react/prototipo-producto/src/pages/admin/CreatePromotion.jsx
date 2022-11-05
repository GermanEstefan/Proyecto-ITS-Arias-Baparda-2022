import { faPlusCircle, faXmark } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import React from "react";
import { useState } from "react";
import { useEffect } from "react";
import { useNavigate } from "react-router-dom";
import { fetchApi } from "../../API/api";
import ContainerBase from "../../components/admin/ContainerBase";
import { useForm } from "../../hooks/useForm";
import imgToBase64 from "../../helpers/imgToBase64";

const CreatePromotion = () => {
  const navigate = useNavigate();
  const [products, setProducts] = useState([]);

  useEffect(() => {
    fetchApi("products.php?BOProducts", "GET")
      .then((res) => {
        console.log(res);
        setProducts(res.result.data);
      })
      .catch((err) => console.log(err));
  }, []);

  const initStatePromoGeneralValues = {
    idProduct: "",
    name: "",
    stock: "",
    price: "",
    description: "",
    contains: [],
  };
  const [promoGeneralValues, handleValuesChange, resetValues] = useForm(
    initStatePromoGeneralValues
  );
  const { idProduct, name, stock, price, description } = promoGeneralValues;

  const initStateContainsPromo = [{ quantity: "", haveProduct: "" }];
  const [containsPromo, setContainsPromo] = useState(initStateContainsPromo);
  const handleAddNewContainPromo = () =>
    setContainsPromo([...containsPromo, { quantity: "", haveProduct: "" }]);
  const handleDeleteContainPromo = (i) => {
    const arrayCopy = [...containsPromo];
    arrayCopy.splice(i, 1);
    setContainsPromo(arrayCopy);
  };
  const handleChangeContainPromo = ({ target }, i) => {
    const arrayModificado = [...containsPromo];
    containsPromo[i][target.name] = target.value;
    setContainsPromo(arrayModificado);
  };

  const initStateLoading = {
    showMessage: false,
    message: "",
    error: false,
  };
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(initStateLoading);

  const handleSubmit = async (e) => {
    e.preventDefault();
    const image = document.getElementById("promo-image");
    const imageToBase64 = await imgToBase64(image.files[0]);
    const bodyOfRequest = {
      ...promoGeneralValues,
      contains: containsPromo,
      picture: imageToBase64,
    };
    setLoading(true);
    console.log(bodyOfRequest);
    try {
      const resp = await fetchApi(
        "products.php?type=promo",
        "POST",
        bodyOfRequest
      );
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
      image.value = "";
      setContainsPromo(initStateContainsPromo);
      return setTimeout(() => setError(initStateLoading), 3000);
    } catch (error) {
      console.error(error);
    } finally {
      setLoading(false);
    }
  };

  return (
    <ContainerBase>
      <section className="container_section create-promo">
        <h1 className="title-promo-product">Crear promocion</h1>
        <p className="text-promo-product">Completar todo los campos</p>

        <div>
          <ul className="switch-form">
            <li
              className="product"
              onClick={() => navigate("/admin/products/create")}
            >
              PRODUCTO
            </li>
            <li
              className="promo"
              onClick={() => navigate("/admin/products-promo/create")}
            >
              PROMO
            </li>
          </ul>

          <form
            onSubmit={handleSubmit}
            className="flex-column-center-xy"
            autoComplete="off"
          >
            <div className="form-row-two-columns-with-label">
              <div>
                <label htmlFor="" className="label-form">
                  Nro de promo
                </label>
                <input
                  type="number"
                  name="idProduct"
                  placeholder="Ingrese N° Promo"
                  onChange={handleValuesChange}
                  value={idProduct}
                  min="1000"
                  className="input-form"
                  required
                />
              </div>
              <div>
                <label htmlFor="" className="label-form">
                  Nombre
                </label>
                <input
                  type="text"
                  name="name"
                  placeholder="Ingrese Nombre"
                  onChange={handleValuesChange}
                  value={name}
                  className="input-form"
                  required
                />
              </div>
            </div>

            <div className="form-row-two-columns-with-label">
              <div>
                <label htmlFor="" className="label-form">
                  Precio Promo (unidad)
                </label>
                <input
                  type="number"
                  name="price"
                  placeholder="Ingrese Precio"
                  onChange={handleValuesChange}
                  value={price}
                  min="0"
                  className="input-form"
                  required
                />
              </div>
              <div>
                <label htmlFor="" className="label-form">
                  Unidades de promocion
                </label>
                <input
                  type="number"
                  name="stock"
                  placeholder="Ingrese Stock promo"
                  min="1"
                  onChange={handleValuesChange}
                  value={stock}
                  className="input-form"
                  required
                />
              </div>
            </div>

            <div className="input-img-container">
              <label className="label-form">Imagen </label>
              <input
                type="file"
                id="promo-image"
                Imagen
                accept=".png, .jpg, .jpeg"
                required
              />
            </div>

            <div className="promo-models-container">
              <FontAwesomeIcon
                className="add-lines"
                icon={faPlusCircle}
                onClick={handleAddNewContainPromo}
              />
              {containsPromo.map((contain, i) => (
                <div key={i} className="form-row-two-columns-with-label">
                  <div>
                    <label htmlFor="" className="label-form">
                      Seleccione producto
                    </label>
                    <select
                      name="haveProduct"
                      value={contain.haveProduct}
                      onChange={(e) => handleChangeContainPromo(e, i)}
                      className="select-form"
                    >
                      <option value="" selected disabled>
                        Seleccione
                      </option>
                      {products.map((product) => (
                        <option key={product.barcode} value={product.barcode}>
                          {" "}
                          {`${product.name} - ${product.design} - ${product.size} - ${product.barcode} `}{" "}
                        </option>
                      ))}
                    </select>
                  </div>
                  <div>
                    <label htmlFor="" className="label-form">
                      Cantidad
                    </label>
                    <input
                      type="number"
                      className="input-form"
                      name="quantity"
                      min="1"
                      value={contain.quantity}
                      onChange={(e) => handleChangeContainPromo(e, i)}
                    />
                  </div>
                  {i !== 0 && (
                    <FontAwesomeIcon
                      className="remove-lines"
                      onClick={() => handleDeleteContainPromo(i)}
                      icon={faXmark}
                    />
                  )}
                </div>
              ))}
            </div>

            <div className="txtarea-container">
              <label htmlFor="" className="label-form">
                Descripcion
              </label>
              <textarea
                name="description"
                placeholder="Ingrese Descripcion"
                required
                onChange={handleValuesChange}
                value={description}
                className="textarea-form"
              ></textarea>
            </div>

            <button
              className={`button-form ${loading && "opacity"}`}
              disabled={loading}
              type="submit"
            >
              {loading ? "CARGANDO..." : "CREAR PROMOCION"}
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
        </div>
      </section>
    </ContainerBase>
  );
};

export default CreatePromotion;
