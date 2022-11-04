import { faCheck, faPencil, faTrash } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import React, { useEffect, useState, useContext } from "react";
import { useNavigate } from "react-router-dom";
import { fetchApi } from "../../API/api";
import { userStatusContext } from "../../App";
import ContainerBase from "../../components/admin/ContainerBase";
import { useForm } from "../../hooks/useForm";

const UserManagment = () => {

  const {rol:rolContext} = useContext(userStatusContext).userData
  const navigate = useNavigate();
  const [employees, setEmployees] = useState([]);
  const [loadingFlags, setLoadingFlags] = useState({ fetchingUsers: true });

  const initStateForm = {
    email: "",
    name: "",
    surname: "",
    password: "",
    rol: "",
    ci: "",
    phone: "",
    address: "",
  };

  const [values, handleValuesChange, resetForm] = useForm(initStateForm);
  const { email, name, surname, password, rol, ci, phone, address } = values;

  const initStateLoading = {
    showMessage: false,
    message: "",
    error: false,
  };

  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(initStateLoading);

  useEffect(() => {
    fetchApi("auth-employees.php", "GET")
      .then((resp) => {
        console.log(resp);
        setEmployees(resp);
      })
      .catch((err) => {
        alert("Error interno");
        console.log(err);
      })
      .finally(() => setLoadingFlags({ fetchingUsers: false }));
  }, []);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    try {
      const resp = await fetchApi(
        "auth-employees.php?url=register",
        "POST",
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
      resetForm();
      return setTimeout(() => setError(initStateLoading), 3000);
    } catch (error) {
      alert("Internal error");
      console.log(error);
    } finally {
      setLoading(false);
    }
  };

  const handleDisableUser = async (idUser) => {
    const confirm = window.confirm(
      "¿Estas seguro que desas desactivar el usuario?"
    );
    if (!confirm) return;
    const resp = await fetchApi(
      `auth-employees.php?idEmployee=${idUser}&action=disable`,
      "PATCH"
    );
    console.log(resp);
    if (resp.status === "error") return alert(resp.result.error_msg);
    const userFiltered = employees.filter((employee) => {
      if (employee.employee_user === idUser) {
        employee.state = "0";
      }
      return employee;
    });
    return setEmployees(userFiltered);
  };

  const handleEnableUser = async (idUser) => {
    const confirm = window.confirm(
      "¿Estas seguro que desas activar el usuario?"
    );
    if (!confirm) return;
    const resp = await fetchApi(
      `auth-employees.php?idEmployee=${idUser}&action=active`,
      "PATCH"
    );
    console.log(resp);
    if (resp.status === "error") return alert(resp.result.error_msg);
    const userFiltered = employees.filter((employee) => {
      if (employee.employee_user === idUser) {
        employee.state = "1";
      }
      return employee;
    });
    return setEmployees(userFiltered);
  };

  return (
    !(rolContext === 'JEFE')
    ?
    <h1>Ruta no permitida para este rol</h1>
    :
    <ContainerBase>
      <section className="container_section list-users">
        {loadingFlags.fetchingUsers ? (
          <span className="fetching-data-message">Obteniendo usuarios ...</span>
        ) : (
          <>
            <h1>Gestion de usuarios</h1>
            <form autoComplete="off" onSubmit={handleSubmit}>
              <h2>Crear un nuevo empleado</h2>
              <div className="form-row-two-columns-with-label">
                <div>
                  <label htmlFor="name" className="label-form">
                    Nombre
                  </label>
                  <input
                    id="name"
                    type="text"
                    className="input-form"
                    onChange={handleValuesChange}
                    name="name"
                    value={name}
                    required
                  />
                </div>
                <div>
                  <label className="label-form" htmlFor="surname">
                    Apellido
                  </label>
                  <input
                    id="surname"
                    type="text"
                    className="input-form"
                    onChange={handleValuesChange}
                    name="surname"
                    value={surname}
                    required
                  />
                </div>
              </div>
              <div className="form-row-two-columns-with-label">
                <div>
                  <label className="label-form" htmlFor="email">
                    Email
                  </label>
                  <input
                    id="email"
                    type="email"
                    className="input-form"
                    onChange={handleValuesChange}
                    name="email"
                    value={email}
                    required
                  />
                </div>
                <div>
                  <label className="label-form" htmlFor="password">
                    Contraseña
                  </label>
                  <input
                    id="password"
                    type="password"
                    className="input-form"
                    onChange={handleValuesChange}
                    name="password"
                    value={password}
                    required
                    minLength={6}
                  />
                </div>
              </div>
              <div className="form-row-two-columns-with-label">
                <div>
                  <label className="label-form" htmlFor="address">
                    Direccion
                  </label>
                  <input
                    id="address"
                    type="text"
                    className="input-form"
                    onChange={handleValuesChange}
                    name="address"
                    value={address}
                    required
                  />
                </div>
                <div>
                  <label className="label-form" htmlFor="phone">
                    Telefono
                  </label>
                  <input
                    id="phone"
                    type="text"
                    className="input-form"
                    onChange={handleValuesChange}
                    name="phone"
                    value={phone}
                    required
                  />
                </div>
              </div>

              <div className="form-row-two-columns-with-label">
                <div>
                  <label className="label-form" htmlFor="">
                    C.I
                  </label>
                  <input
                    type="number"
                    value={ci}
                    onChange={handleValuesChange}
                    name="ci"
                    required
                    min="1"
                    className="input-form"
                  />
                </div>

                <div>
                  <label className="label-form" htmlFor="rol">
                    Rol:
                  </label>
                  <select
                    id="rol"
                    onChange={handleValuesChange}
                    name="rol"
                    value={rol}
                    required
                    className="select-form"
                  >
                    <option selected value="">
                      Seleccione un Rol
                    </option>
                    <option value="JEFE">Jefe</option>
                    <option value="VENDEDOR">Vendedor</option>
                    <option value="COMPRADOR">Comprador</option>
                  </select>
                </div>
              </div>
              <button
                className={`button-form ${loading && "opacity"}`}
                disabled={loading}
              >
                {loading ? "Cargando..." : "CREAR USUARIO"}
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

            <table className="table-template">
              <caption>Lista de usuarios</caption>
              <tbody>
                <tr>
                  <th>Id</th>
                  <th>C.I</th>
                  <th>Rol</th>
                  <th>Email</th>
                  <th>Nombre</th>
                  <th>Apellido</th>
                  <th>Telefono</th>
                  <th>Direccion</th>
                  <th>Estado</th>
                  <th colSpan={2}>Controles</th>
                </tr>
                {employees.map((employe) => (
                  <tr key={employe.employee_user}>
                    <td>{employe.employee_user}</td>
                    <td>{employe.ci}</td>
                    <td>{employe.employee_role}</td>
                    <td>{employe.email}</td>
                    <td>{employe.name}</td>
                    <td>{employe.surname}</td>
                    <td>{employe.phone}</td>
                    <td>{employe.address}</td>
                    <td>{employe.state}</td>
                    <td
                      className="controls-table"
                      onClick={() =>
                        navigate(`/admin/users/edit/${employe.employee_user}`)
                      }
                    >
                      <FontAwesomeIcon icon={faPencil} />
                    </td>
                    {employe.state === "1" ? (
                      <td
                        className="warning-control"
                        onClick={() => handleDisableUser(employe.employee_user)}
                      >
                        <FontAwesomeIcon icon={faTrash} />
                      </td>
                    ) : (
                      <td
                        className="successfully-control"
                        onClick={() => handleEnableUser(employe.employee_user)}
                      >
                        <FontAwesomeIcon icon={faCheck} />
                      </td>
                    )}
                  </tr>
                ))}
              </tbody>
            </table>
          </>
        )}
      </section>
    </ContainerBase>
  );
};

export default UserManagment;
