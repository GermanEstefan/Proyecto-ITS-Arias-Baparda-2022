
import React from 'react'
import { useState } from 'react';
import { fetchApi } from '../../API/api';
import ContainerBase from '../../components/admin/ContainerBase';

import { useForm } from '../../hooks/useForm';

const CreateUser = () => {

    const initStateForm = {
        email: "pepito@pepe.com",
        name: "pepito",
        surname: "pepon",
        password: "123456",
        rol: "COMPRADOR",
        ci: "50123132",
        phone: "123123123",
        address: "spadsadlñksald"
    }
    const [values, handleValuesChange, resetForm] = useForm(initStateForm);
    const { email, name, surname, password, rol, ci, phone, address } = values;

    const initStateLoading = {
        showMessage: false,
        message: '',
        error: false
    };
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState(initStateLoading);

    const handleSubmit = async (e) => {
        e.preventDefault();
        setLoading(true);
        try {
            const resp = await fetchApi('auth-employees.php?url=register', 'POST', values);
            console.log(resp)
            if (resp.status === 'error') {
                setError({ showMessage: true, message: resp.result.error_msg, error: true });
                return setTimeout(() => setError(initStateLoading), 3000)
            }
            setError({ showMessage: true, message: resp.result.msg, error: false });
            resetForm();
            return setTimeout(() => setError(initStateLoading), 3000)
        } catch (error) {
            alert('Internal error');
            console.log(error);
        } finally {
            setLoading(false)
        }

    }

    return (
        <ContainerBase>
            <section className="container_section create-user">
               
                <form autoComplete="off" onSubmit={handleSubmit} >
                    <h1>Crear un nuevo empleado</h1>
                    <div className='form-row-two-columns-with-label'>
                        <div>
                            <label htmlFor="name" className='label-form'>Nombre</label>
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
                            <label className='label-form' htmlFor="surname">Apellido</label>
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
                    <div className='form-row-two-columns-with-label'>
                        <div>
                            <label className='label-form' htmlFor="email" >Email</label>
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
                            <label className='label-form' htmlFor="password" >Contraseña</label>
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
                    <div className='form-row-two-columns-with-label'>
                        <div>
                            <label className='label-form' htmlFor="address" >Direccion</label>
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
                            <label className='label-form' htmlFor="phone">Telefono</label>
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

                    <div className='form-row-two-columns-with-label'>
                        <div>
                            <label className='label-form' htmlFor="">C.I</label>
                            <input
                                type="text"
                                value={ci}
                                onChange={handleValuesChange}
                                name="ci"
                                required
                                className='input-form'
                            />
                        </div>

                        <div>
                            <label className='label-form' htmlFor="rol">Rol:</label>
                            <select
                                id="rol"
                                onChange={handleValuesChange}
                                name="rol"
                                value={rol}
                                required
                                className='select-form'
                            >
                                <option value='JEFE'>Jefe</option>
                                <option value='VENDEDOR'>Vendedor</option>
                                <option value='COMPRADOR'>Comprador</option>
                            </select>
                        </div>

                    </div>
                    <button
                        className={`button-form ${loading && 'opacity'}`}
                        disabled={loading}
                    >{loading ? 'Cargando...' : 'CREAR USUARIO'}</button>
                    {
                        error.showMessage &&
                        <span className={`${error.error ? 'warning-message' : 'successfully-message'} `} >{error.message}</span>
                    }
                </form>
            </section>
        </ContainerBase>
    )
}

export default CreateUser;
