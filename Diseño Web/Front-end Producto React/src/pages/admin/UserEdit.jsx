
import React, { useContext, useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import { fetchApi } from "../../API/api";
import { userStatusContext } from "../../App";
import ContainerBase from "../../components/admin/ContainerBase";

const UserEdit = () => {

    const { rol: rolContext } = useContext(userStatusContext).userData
    const { idUser } = useParams();
    const [userValues, setUserValues] = useState({
        name,
        surname,
        password,
        address,
        phone,
        rol
    })

    const {
        name,
        surname,
        password,
        address,
        phone,
        rol
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

    const handleEditUser = async (e) => {
        e.preventDefault();
        setLoading(true);
        try {
            const resp = await fetchApi(`auth-employees.php?idEmployee=${idUser}&action=edit`, 'PATCH', userValues);            
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
        fetchApi(`auth-employees.php?idEmployee=${idUser}`, 'GET')
            .then(res => {
                console.log(res)
                const userData = res.result.data;
                setUserValues({
                    name: userData.name,
                    surname: userData.surname,
                    password: "",
                    phone: userData.phone,
                    rol: userData.employee_role,
                    address: userData.address
                })
            })
            .catch(err => console.error(err))
    }, [])

    return (
        !(rolContext === 'JEFE')
            ?
            <h1>Ruta no permitida para este rol</h1>
            :
            <ContainerBase>
                <section className="container_section edit-user-admin flex-column-center-xy">
                    <h1>Editar usuario</h1>
                    <form onSubmit={handleEditUser} className="flex-column-center-xy">

                        <div className='form-row-two-columns-with-label'>
                            <div>
                                <label>Nombre: </label>
                                <input
                                    type="text"
                                    onChange={handleChangeInputs}
                                    name='name'
                                    value={name}
                                    className='input-form'
                                    required
                                />
                            </div>
                            <div>
                                <label>Apellido: </label>
                                <input
                                    type="text"
                                    onChange={handleChangeInputs}
                                    name='surname'
                                    value={surname}
                                    className='input-form'
                                    required
                                />
                            </div>
                        </div>

                        <div className='form-row-two-columns-with-label'>
                            <div>
                                <label>Contraseña: </label>
                                <input
                                    type="password"
                                    onChange={handleChangeInputs}
                                    name='password'
                                    value={password}
                                    className='input-form'
                                    required
                                />
                            </div>
                            <div>
                                <label>Direccion: </label>
                                <input
                                    type="text"
                                    onChange={handleChangeInputs}
                                    name='address'
                                    value={address}
                                    className='input-form'
                                    required
                                />
                            </div>
                        </div>

                        <div className='form-row-two-columns-with-label'>
                            <div>
                                <label>Telefono: </label>
                                <input
                                    type="text"
                                    onChange={handleChangeInputs}
                                    name='phone'
                                    value={phone}
                                    className='input-form'
                                    required
                                />
                            </div>

                            <div>
                                <label>Rol: </label>
                                <select
                                    onChange={handleChangeInputs}
                                    name='rol'
                                    value={rol}
                                    className='select-form'
                                    required
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