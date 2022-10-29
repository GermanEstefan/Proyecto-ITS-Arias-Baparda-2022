
import { faPencil, faTrash } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import React, { useEffect, useState } from 'react'
import { useNavigate } from 'react-router-dom';
import { fetchApi } from '../../API/api';
import ContainerBase from '../../components/admin/ContainerBase';
import { useForm } from '../../hooks/useForm';

const Sizes = () => {

    const navigate = useNavigate();
    const [values, handleValuesChange, resetForm] = useForm({ name: '', description: '' });
    const { name, description } = values;
    const [loadingFlags, setLoadingFlags] = useState({ fetchingSizes: true, createSize: false });
    const [sizes, setSizes] = useState([]);
    const initStateLoading = {
        showMessage: false,
        message: '',
        error: false
    };
    const [error, setError] = useState(initStateLoading);

    useEffect(() => {
        fetchApi('sizes.php', 'GET')
            .then(sizes => {
                console.log(sizes);
                setSizes(sizes.result.data);
            })
            .catch(err => console.error(err))
            .finally(() => setLoadingFlags({ ...loadingFlags, fetchingSizes: false }))
    }, [])

    const handleCreateSizes = async (e) => {
        e.preventDefault();
        setLoadingFlags({ ...loadingFlags, createSize: true });
        try {
            const resp = await fetchApi('sizes.php', 'POST', values);
            console.log(resp)
            if (resp.status === 'error') {
                setError({ showMessage: true, message: resp.result.error_msg, error: true });
                return setTimeout(() => setError(initStateLoading), 3000)
            }
            const lastIdOfSize = parseInt(sizes[sizes.length - 1].id_size) + 1
            setError({ showMessage: true, message: resp.result.msg, error: false });
            setSizes([...sizes, { name: values.name, description: values.description, id_size: lastIdOfSize }])
            resetForm();
            return setTimeout(() => setError(initStateLoading), 3000)
        } catch (error) {
            alert('Internal error');
            console.log(error);
        } finally {
            setLoadingFlags({ ...loadingFlags, createSize: false });
        }

    }

    const handleDeleteSize = async (idSize) => {
        setLoadingFlags({ ...loadingFlags, deleteSize: true });
        const confirm = window.confirm('¿Estas seguro que desas borrar el talle?')
        if (!confirm) return;
        try {
            const resp = await fetchApi(`sizes.php?idSize=${idSize}`, 'DELETE');
            console.log(resp);
            if (resp.status === 'error') {
                return alert(resp.result.error_msg);
            }
            const sizeFiltered = sizes.filter(size => size.id_size !== idSize);
            setSizes(sizeFiltered);
        } catch (error) {
            console.error(error);
        } finally {
            setLoadingFlags({ ...loadingFlags, deleteSize: false });
        }
    }

    return (
        <ContainerBase>
            <section className='container_section generals-layout flex-column-center-xy'>

                {
                    loadingFlags.fetchingSizes
                        ? <span>Obteniendo talles...</span>
                        : <>
                            <h1>Talles</h1>
                            <div>
                                <form onSubmit={handleCreateSizes} autoComplete="off">
                                    <label htmlFor="">Nombre</label>
                                    <input
                                        type="text"
                                        className='input-form'
                                        required
                                        value={name}
                                        name='name'
                                        onChange={handleValuesChange}
                                    />

                                    <label htmlFor="">Descripcion</label>
                                    <input
                                        type="text"
                                        className='input-form'
                                        required
                                        value={description}
                                        name='description'
                                        onChange={handleValuesChange}
                                    />
                                    <button
                                        className={`button-form ${loadingFlags.createCategory && 'opacity'}`}
                                        disabled={loadingFlags.createCategory}
                                    >{loadingFlags.createCategory ? 'Cargando...' : 'CREAR TALLE'}</button>
                                    {
                                        error.showMessage &&
                                        <span className={`${error.error ? 'warning-message' : 'successfully-message'} `} >{error.message}</span>
                                    }
                                </form>
                            </div>

                            <div>
                                <h2>Lista de talles</h2>
                                {
                                    !sizes.length === 0
                                        ? <span>No hay talles creadas</span>
                                        : <table className='table-template'>
                                            <tbody>
                                                <tr>
                                                    <th>Id</th>
                                                    <th>Nombre</th>
                                                    <th>Descripcion</th>
                                                    <th colSpan={2}>Controles</th>
                                                </tr>
                                                {
                                                    sizes.map(size => (
                                                        !(size.id_size === '1')
                                                        &&
                                                        <tr key={size.id_size}>
                                                            <td>{size.id_size}</td>
                                                            <td>{size.name}</td>
                                                            <td>{size.description}</td>
                                                            <td className="controls-table" onClick={() => navigate(`/admin/generals/sizes/edit/${size.id_size}`)}><FontAwesomeIcon icon={faPencil} /></td>
                                                            <td className="controls-table" onClick={() => handleDeleteSize(size.id_size)} ><FontAwesomeIcon icon={faTrash} /></td>
                                                        </tr>
                                                    ))
                                                }
                                            </tbody>
                                        </table>
                                }
                            </div>
                        </>
                }
            </section>
        </ContainerBase>
    )
}

export default Sizes;
