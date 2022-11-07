
import { faPencil, faTrash } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import React, { useState } from 'react'
import { useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import Swal from 'sweetalert2';
import { fetchApi } from '../../API/api';
import ContainerBase from '../../components/admin/ContainerBase';
import { useForm } from '../../hooks/useForm';

const Design = () => {

    const navigate = useNavigate();
    const [values, handleValuesChange, resetForm] = useForm({ name: '', description: '' });
    const { name, description } = values;
    const initStateLoading = {
        showMessage: false,
        message: '',
        error: false
    };
    const [loadingFlags, setLoadingFlags] = useState({ createDesign: false, fetchingDesigns: true });
    const [error, setError] = useState(initStateLoading);

    const [designs, setDesigns] = useState([]);
    useEffect(() => {
        fetchApi('designs.php', 'GET')
            .then(res => {
                console.log(res)
                setDesigns(res.result.data)
            })
            .catch(error => {
                console.error(error);
                alert('Error interno');
            })
            .finally(() => setLoadingFlags({ ...loadingFlags, fetchingDesigns: false }))
    }, [])

    const handleCreateDesign = async (e) => {
        e.preventDefault();
        setLoadingFlags({ ...loadingFlags, createDesign: true });
        try {
            const resp = await fetchApi('designs.php', 'POST', values);
            console.log(resp)
            if (resp.status === 'error') {
                setError({ showMessage: true, message: resp.result.error_msg, error: true });
                return setTimeout(() => setError(initStateLoading), 3000)
            }
            const lastIdOfCategory = parseInt(designs[designs.length - 1 ].id_design) + 1
            setError({ showMessage: true, message: resp.result.msg, error: false });
            setDesigns([ ...designs, {name: values.name, description: values.description, id_design: lastIdOfCategory }])
            resetForm();
            return setTimeout(() => setError(initStateLoading), 3000)
        } catch (error) {
            alert('Internal error');
            console.log(error);
        } finally {
            setLoadingFlags({ ...loadingFlags, createCategory: false });
        }

    }

    const handleDeleteDesign = async (idDesign) => {
        const confirm = window.confirm('¿Estas seguro que desas borrar el diseño?')
        if(!confirm) return;
        const resp = await fetchApi(`designs.php?idDesign=${idDesign} `, 'DELETE');
        console.log(resp)
        if(resp.status === 'error'){
            return Swal.fire({
                icon: "error",
                text: resp.result.error_msg,
                timer: 2000,
                showConfirmButton: false,
            });
        }
        const designsFiltered = designs.filter( design => design.id_design !== idDesign);
        return setDesigns(designsFiltered);
    }


    return (
        <ContainerBase>
            <section className="container_section categorys-manage generals-layout flex-column-center-xy">
                {
                    loadingFlags.fetchingDesigns
                        ? <span className='fetching-data-message'>Obteniendo diseños...</span>
                        : <>
                            <h1>Diseños</h1>
                            <div>
                                <h2>Crear diseño</h2>
                                <form onSubmit={handleCreateDesign} autoComplete="off">

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
                                    >{loadingFlags.createCategory ? 'Cargando...' : 'CREAR DISEÑO'}</button>
                                    {
                                        error.showMessage &&
                                        <span className={`${error.error ? 'warning-message' : 'successfully-message'} `} >{error.message}</span>
                                    }
                                </form>
                            </div>

                            <div>
                                <h2>Lista de diseños</h2>
                                {
                                    !designs.length === 0 
                                        ? <span>No hay diseños creados</span>
                                        : <table className='table-template'>
                                            <tbody>
                                                <tr>
                                                    <th>Id</th>
                                                    <th>Nombre</th>
                                                    <th>Descripcion</th>
                                                    <th colSpan={2}>Controles</th>
                                                </tr>
                                                {
                                                    designs.map( design => (
                                                        !(design.id_design === '1')
                                                        &&
                                                        <tr key={design.id_design}>
                                                            <td>{design.id_design}</td>
                                                            <td>{design.name}</td>
                                                            <td>{design.description}</td>
                                                            <td className="controls-table"  onClick={() => navigate(`/admin/generals/designs/edit/${design.id_design}`)}><FontAwesomeIcon icon={faPencil} /></td>
                                                            <td className="controls-table" onClick={() => handleDeleteDesign(design.id_design)} ><FontAwesomeIcon icon={faTrash} /></td>
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

export default Design;
