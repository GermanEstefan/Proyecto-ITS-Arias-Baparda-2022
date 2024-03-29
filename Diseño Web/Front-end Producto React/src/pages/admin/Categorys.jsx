import { faPencil, faTrash } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import React, { useState } from "react";
import { useEffect } from "react";
import { useNavigate } from "react-router-dom";
import Swal from "sweetalert2";
import { fetchApi } from "../../API/api";
import ContainerBase from "../../components/admin/ContainerBase";
import imgToBase64 from "../../helpers/imgToBase64";
import { useForm } from "../../hooks/useForm";

const Categorys = () => {
  const navigate = useNavigate();
  const [values, handleValuesChange, resetForm] = useForm({
    name: "",
    description: "",
  });
  const { name, description } = values;
  const initStateLoading = {
    showMessage: false,
    message: "",
    error: false,
  };
  const [loadingFlags, setLoadingFlags] = useState({
    createCategory: false,
    fetchingCategorys: true,
  });
  const [error, setError] = useState(initStateLoading);

  const [categorys, setCategorys] = useState([]);
  useEffect(() => {
    fetchApi("categorys.php", "GET")
      .then((res) => {
        
        if (res.result.data.length === 0) return;
        setCategorys(res.result.data);
      })
      .catch((error) => {
        console.error(error);
        alert("Error interno");
      })
      .finally(() =>
        setLoadingFlags({ ...loadingFlags, fetchingCategorys: false })
      );
  }, []);

  const handleCreateCategory = async (e) => {
    e.preventDefault();
    setLoadingFlags({ ...loadingFlags, createCategory: true });
    const image = document.getElementById("image-category");
    const imageToBase64 = await imgToBase64(image.files[0]);
    const bodyOfRequest = { ...values, picture: imageToBase64 };
    try {
      
      const resp = await fetchApi("categorys.php", "POST", bodyOfRequest);
      
      if (resp.status === "error") {
        setError({
          showMessage: true,
          message: resp.result.error_msg,
          error: true,
        });
        return setTimeout(() => setError(initStateLoading), 3000);
      }
      const lastIdOfCategory =
        parseInt(categorys[categorys.length - 1].id_category) + 1;
      setError({ showMessage: true, message: resp.result.msg, error: false });
      setCategorys([
        ...categorys,
        {
          name: values.name,
          description: values.description,
          id_category: lastIdOfCategory,
        },
      ]);
      resetForm();
      image.value = "";
      return setTimeout(() => setError(initStateLoading), 3000);
    } catch (error) {
      alert("Internal error");
      console.log(error);
    } finally {
      setLoadingFlags({ ...loadingFlags, createCategory: false });
    }
  };

  const handleDeleteCategory = async (idCategory) => {
    const confirm = window.confirm(
      "¿Estas seguro que desas borrar la categoria?"
    );
    if (!confirm) return;
    const resp = await fetchApi(
      `categorys.php?idCategory=${idCategory} `,
      "DELETE"
    );
    if (resp.status === "error") {
      return Swal.fire({
        icon: "error",
        text: resp.result.error_msg,
        timer: 2000,
        showConfirmButton: false,
      });

    }
    const categorysFiltered = categorys.filter(
      (category) => category.id_category !== idCategory
    );
    return setCategorys(categorysFiltered);
  };

  return (
    <ContainerBase>
      <section className="container_section categorys-manage generals-layout flex-column-center-xy">
        {loadingFlags.fetchingCategorys ? (
          <span className="fetching-data-message">
            Obteniendo categorias...
          </span>
        ) : (
          <>
            <h1>Categorias</h1>
            <div>
              <h2>Crear categoria</h2>
              <form onSubmit={handleCreateCategory} autoComplete="off">
                <label htmlFor="">Nombre</label>

                <input
                  type="text"
                  className="input-form"
                  required
                  value={name}
                  name="name"
                  onChange={handleValuesChange}
                />

                <label htmlFor="">Descripcion</label>
                <input
                  type="text"
                  className="input-form"
                  required
                  value={description}
                  name="description"
                  onChange={handleValuesChange}
                />

                <label htmlFor="">Imagen</label>
                <input
                  type="file"
                  required
                  id="image-category"
                  accept=".png, .jpg, .jpeg"
                />

                <button
                  className={`button-form ${loadingFlags.createCategory &&
                    "opacity"}`}
                  disabled={loadingFlags.createCategory}
                >
                  {loadingFlags.createCategory
                    ? "Cargando..."
                    : "CREAR CATEGORIA"}
                </button>
                {error.showMessage && (
                  <span
                    className={`${error.error ? "warning-message" : "successfully-message"
                      } `}
                  >
                    {error.message}
                  </span>
                )}
              </form>
            </div>

            <div>
              <h2>Lista de categorias</h2>
              {!categorys.length === 0 ? (
                <span>No hay categorias creadas</span>
              ) : (
                <table className="table-template">
                  <tbody>
                    <tr>
                      <th>Id</th>
                      <th>Nombre</th>
                      <th>Descripcion</th>
                      <th colSpan={2}>Controles</th>
                    </tr>
                    {categorys.map((category) => (
                      <tr key={category.id_category}>
                        <td>{category.id_category}</td>
                        <td>{category.name}</td>
                        <td>{category.description}</td>
                        <td
                          className="controls-table"
                          onClick={() =>
                            handleDeleteCategory(category.id_category)
                          }
                        >
                          <FontAwesomeIcon icon={faTrash} />
                        </td>
                        <td
                          className="controls-table"
                          onClick={() =>
                            navigate(
                              `/admin/generals/categorys/edit/${category.id_category}`
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
            </div>
          </>
        )}
      </section>
    </ContainerBase>
  );
};

export default Categorys;
