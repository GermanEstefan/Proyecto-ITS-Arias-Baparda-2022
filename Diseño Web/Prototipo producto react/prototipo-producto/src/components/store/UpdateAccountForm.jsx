
import React from "react";
import { useContext, useState } from "react";
import { userStatusContext } from "../../App";
import { isEmpty } from "../../helpers/validateForms";
import { useForm } from "../../hooks/useForm";
import Input from "./Input";

const UpdateAccountForm = () => {

    const {userData} = useContext(userStatusContext);

    const [values, handleValuesChange] = useForm({
        name: userData.name, 
        surname: userData.surname, 
        address: userData.address,
        phone: userData.phone
    });

    const [errorStatusForm, setErrorStatusForm] = useState({ name: true, surname: true })

    const handleSubmit = (e) => {
        e.preventDefault();
        if(Object.values(errorStatusForm).includes(true)) return;
        alert('Submit');
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
                    value = {values.name}
                    name = 'name'
                    setErrorStatusForm = {setErrorStatusForm}
                />
            </div>

            <div>
                <label htmlFor="">Apellido: </label>
                <Input
                    onChange={handleValuesChange}
                    validateFunction={isEmpty}
                    value = {values.surname}
                    setErrorStatusForm = {setErrorStatusForm}
                    name = 'surname'
                />
            </div>

            <div>
                <label htmlFor="">Direccion: </label>
                <input
                    type="text"
                    onChange={handleValuesChange}
                    value = {values.address}
                    name = 'address'
                />
            </div>

            <div>
                <label htmlFor="">Telefono: </label>
                <input
                    type="text"
                    onChange={handleValuesChange}
                    value = {values.phone}
                    name = 'phone'
                />
            </div>

            <button className="submit-button">Modificar</button>

        </form>
    )
}

export default UpdateAccountForm;