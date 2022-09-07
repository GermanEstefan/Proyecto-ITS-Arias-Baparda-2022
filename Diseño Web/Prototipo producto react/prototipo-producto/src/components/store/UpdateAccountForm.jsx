
import React from "react";
import { isEmpty } from "../../helpers/validateForms";
import { useForm } from "../../hooks/useForm";
import Input from "./Input";

const UpdateAccountForm = () => {

    const [values, handleValuesChange] = useForm({});

    const handleSubmit = (e) => {
        e.preventDefault();
        alert('Submit');
    }

    return (
        <form onSubmit={handleSubmit} className="">

            <h2>Datos personales</h2>

            <div>
                <label htmlFor="">Nombre: </label>
                <Input
                    onChange={handleValuesChange}
                    validateFunction={isEmpty}
                />
            </div>

            <div>
                <label htmlFor="">Apellido: </label>
                <Input
                    onChange={handleValuesChange}
                    validateFunction={isEmpty}
                />
            </div>

            <div>
                <label htmlFor="">Direccion: </label>
                <input
                    type="text"
                    onChange={handleValuesChange}
                />
            </div>

            <div>
                <label htmlFor="">Telefono: </label>
                <input
                    type="text"
                    onChange={handleValuesChange}
                />
            </div>

            <button className="submit-button">Modificar</button>

        </form>
    )
}

export default UpdateAccountForm;