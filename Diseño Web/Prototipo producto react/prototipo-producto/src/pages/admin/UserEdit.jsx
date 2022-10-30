
import React, { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import { fetchApi } from "../../API/api";
import ContainerBase from "../../components/admin/ContainerBase";

const UserEdit = () => {

    const { idUser } = useParams();

    const [userValues, setUserValues] = useState({
        nameEmployee,
        surnameEmployee,
        passwordEmployee,
        addressEmployee,
        phoneEmployee,
        rolEmployee
    })

    const {
        nameEmployee,
        surnameEmployee,
        passwordEmployee,
        addressEmployee,
        phoneEmployee,
        rolEmployee
    } = userValues;

    const handleChangeInputs = ({ target }) => {
        setUserValues({
            ...userValues,
            [target.name]: target.value
        })
    }
    const initStateLoading = {
        showMessage: false,
        message: '',
        error: false
    };

    const [error, setError] = useState(initStateLoading);
    const [loading, setLoading] = useState(false);

    const handleSubmit = async (e) => {
        e.preventDefault();
        setLoading(true);
        try {
            const resp = await fetchApi(`${idUser}`, 'PATCH', userValues);
            if (resp.status === 'error') {
                setError({ showMessage: true, message: resp.result.error_msg, error: true });
                return setTimeout(() => setError(initStateLoading), 3000)
            }
            setError({ showMessage: true, message: resp.result.msg, error: false });
            return setTimeout(() => setError(initStateLoading), 3000)
        } catch (error) {
            console.error(error);
        } finally {
            setLoading(false);
        }
    }

    useEffect(() => {
        fetchApi(`${idUser}`,'GET')
            .then(res => {
                const userData = res.result.data;
                setUserValues({})
            })
            .catch(err => console.error(err))
    },[])

    return (
        <ContainerBase>
            <section className="container_section edit-user-admin flex-column-center-xy">
                <h1>Editar usuario</h1>
                <form onSubmit={handleSubmit} className="flex-column-center-xy">

                    <div className='form-row-two-columns-with-label'>
                        <div>
                            <label>Nombre: </label>
                            <input
                                type="text"
                                onChange={handleChangeInputs}
                                name='nameEmployee'
                                value={nameEmployee}
                                className='input-form'
                            />
                        </div>
                        <div>
                            <label>Apellido: </label>
                            <input
                                type="text"
                                onChange={handleChangeInputs}
                                name='surnameEmployee'
                                value={surnameEmployee}
                                className='input-form'
                            />
                        </div>
                    </div>

                    <div className='form-row-two-columns-with-label'>
                        <div>
                            <label>Contraseña: </label>
                            <input
                                type="password"
                                onChange={handleChangeInputs}
                                name='passwordEmployee'
                                value={passwordEmployee}
                                className='input-form'
                            />
                        </div>
                        <div>
                            <label>Direccion: </label>
                            <input
                                type="text"
                                onChange={handleChangeInputs}
                                name='addressEmployee'
                                value={addressEmployee}
                                className='input-form'
                            />
                        </div>
                    </div>

                    <div className='form-row-two-columns-with-label'>
                        <div>
                            <label>Telefono: </label>
                            <input
                                type="text"
                                onChange={handleChangeInputs}
                                name='phoneEmployee'
                                value={phoneEmployee}
                                className='input-form'
                            />
                        </div>

                        <div>
                            <label>Rol: </label>
                            <select
                                onChange={handleChangeInputs}
                                name='rolEmployee'
                                value={rolEmployee}
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
                    >{loading ? 'Cargando...' : 'EDITAR USUARIO'}</button>
                    {
                        error.showMessage &&
                        <span className={`${error.error ? 'warning-message' : 'successfully-message'} `} >{error.message}</span>
                    }
                    
                </form>
            </section>
        </ContainerBase>
    )
}

export default UserEdit;