
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

    return(
        <form onSubmit={handleSubmit}>
            
            <label htmlFor="">Nombre: </label>
            <Input
                onChange = {handleValuesChange}
                validateFunction = {isEmpty}
            />

            <label htmlFor="">Apellido: </label>
            <Input
                onChange = {handleValuesChange}
                validateFunction = {isEmpty}
            />

            <label htmlFor="">Direccion: </label>
            <input 
                type="text" 
                onChange={handleValuesChange} 
            />

            <label htmlFor="">Telefono: </label>
            <input 
                type="text" 
                onChange={handleValuesChange} 
            />

        </form>
    )
}

export default UpdateAccountForm;