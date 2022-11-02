import React from "react";
import { useState, useEffect } from "react";
import { useParams } from "react-router-dom";
import { fetchApi } from "../../API/api";
import ContainerBase from "../../components/admin/ContainerBase";

const EditModelProduct = () => {
  const { barcode } = useParams();
  const [values, setValues] = useState({
    stock: "",
    prodDesign: "",
    prodSize: "",
    nameCurrent: "",
  });
  const { stock, prodDesign, prodSize, nameCurrent } = values;
  const handleChangeInputs = ({ target }) => {
    setValues({
      ...values,
      [target.name]: target.value,
    });
  };
  const [selectValues, setSelectValues] = useState({ sizes: [], designs: [] });
  const initStateLoading = {
    showMessage: false,
    message: "",
    error: false,
  };
  const [error, setError] = useState(initStateLoading);
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    const productPromise = fetchApi(`products.php?BObarcode=${barcode}`, "GET");
    const sizesPromise = fetchApi("sizes.php", "GET");
    const designsPromise = fetchApi("designs.php", "GET");
    Promise.all([productPromise, sizesPromise, designsPromise]).then(
      ([product, sizes, designs]) => {
        const { idDesign, stock, idSize, name } = product.result.data;
        setValues({
          stock: stock,
          prodDesign: idDesign,
          prodSize: idSize,
          nameCurrent: name,
        });
        setSelectValues({
          sizes: sizes.result.data,
          designs: designs.result.data,
        });
      }
    );
  }, []);

  const handleEdit = async (e) => {
    e.preventDefault();
    setLoading(true);
    try {
      const resp = await fetchApi(
        `products.php?barcode=${barcode}&actionMin=edit`,
        "PATCH",
        values
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
      return setTimeout(() => setError(initStateLoading), 3000);
    } catch (error) {
      console.error(error);
    } finally {
      setLoading(false);
    }
  };

  return (
    <ContainerBase>
      <section className="container_section edit-model flex-column-center-xy">
        <form
          onSubmit={handleEdit}
          autoComplete="off"
          className="flex-column-center-xy"
        >
          <h2>Editar modelo</h2>
          <div>
            <label>Name:</label>
            <input
              type="text"
              name="name"
              onChange={handleChangeInputs}
              value={nameCurrent}
              readOnly
              className="select-form opacity"
            />
          </div>

          <div>
            <label htmlFor="" className="label-form">
              Talle:
            </label>
            <select
              required
              className="select-form"
              name="prodSize"
              onChange={handleChangeInputs}
              value={prodSize}
            >
              {selectValues.sizes.map((size) => (
                <option
                  key={size.id_size}
                  selected={size.name === size ? true : false}
                  value={size.id_size}
                >
                  {size.name}
                </option>
              ))}
            </select>
          </div>

          <div>
            <label htmlFor="" className="label-form">
              Diseño:
            </label>
            <select
              required
              className="select-form"
              onChange={handleChangeInputs}
              name="prodDesign"
              value={prodDesign}
            >
              {selectValues.designs.map((design) => (
                <option
                  value={design.id_design}
                  selected={design.name === prodDesign ? true : false}
                  key={design.id_design}
                >
                  {design.name}
                </option>
              ))}
            </select>
          </div>

          <div>
            <label htmlFor="" className="label-form">
              Stock:
            </label>
            <input
              required
              type="number"
              onChange={handleChangeInputs}
              className="input-form"
              name="stock"
              value={stock}
            />
          </div>

          <button
            className={`button-form ${loading && "opacity"}`}
            disabled={loading}
          >
            {loading ? "Cargando..." : "EDITAR TALLE"}
          </button>
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

export default EditModelProduct;
