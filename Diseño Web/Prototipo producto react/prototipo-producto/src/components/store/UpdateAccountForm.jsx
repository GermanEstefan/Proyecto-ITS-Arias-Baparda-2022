
import React from "react";
import { useContext, useState } from "react";
import { userStatusContext } from "../../App";
import { isEmpty } from "../../helpers/validateForms";
import { useForm } from "../../hooks/useForm";
import Swal from "sweetalert2";
import Input from "./Input";
import { fetchApi } from "../../API/api";

const UpdateAccountForm = () => {

    const { userData } = useContext(userStatusContext);
    const [values, handleValuesChange] = useForm({
        name: userData.name,
        surname: userData.surname,
        address: userData.address || '',
        phone: userData.phone || ''
    });

    const [errorStatusForm, setErrorStatusForm] = useState({ nameCurrent: false, surname: false })

    const handleSubmit = async (e) => {
        e.preventDefault();
        if (Object.values(errorStatusForm).includes(true)) return;
        try {
            const resp = await fetchApi("auth-customers.php?url=update", "PUT", values)
            if (resp.status === 'successfully') {
                Swal.fire({
                    icon: "success",
                    text: "Datos acutalizados correctamente",
                    timer: 2000,
                    showConfirmButton: false,
                });
            } else {
                return Swal.fire({
                    icon: "error",
                    text: resp.result.error_msg,
                    timer: 3000,
                    showConfirmButton: true,
                });
            }
        } catch (error) {
            return Swal.fire({
                icon: "error",
                text: "Error 500, servidor caido",
                timer: 3000,
                showConfirmButton: true,
            });
        }
    }

    return (
        <form onSubmit={handleSubmit} className="">

            <h2>Datos personales</h2>

            <label htmlFor="">Email:</label>
            <span>{userData.email}</span>

            <div>
                <label htmlFor="">Nombre: </label>
                <Input
                    onChange={handleValuesChange}
                    validateFunction={isEmpty}
                    value={values.name}
                    name='name'
                    setErrorStatusForm={setErrorStatusForm}
                />
            </div>

            <div>
                <label htmlFor="">Apellido: </label>
                <Input
                    onChange={handleValuesChange}
                    validateFunction={isEmpty}
                    value={values.surname}
                    setErrorStatusForm={setErrorStatusForm}
                    name='surname'
                />
            </div>

            <div>
                <label htmlFor="">Direccion: </label>
                <input
                    type="text"
                    onChange={handleValuesChange}
                    value={values.address}
                    name='address'
                />
            </div>

            <div>
                <label htmlFor="">Telefono: </label>
                <input
                    type="text"
                    onChange={handleValuesChange}
                    value={values.phone}
                    name='phone'
                />
            </div>

            <button className="submit-button">Modificar</button>

        </form>
    )
}

export default UpdateAccountForm;