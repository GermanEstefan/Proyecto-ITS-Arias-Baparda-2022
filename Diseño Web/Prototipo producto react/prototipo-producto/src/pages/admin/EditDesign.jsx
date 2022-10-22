
import React, { useState, useEffect } from "react";
import { useParams } from "react-router-dom";
import { fetchApi } from "../../API/api";
import ContainerBase from "../../components/admin/ContainerBase";

const EditDesign = () => {

    const { idOfDesign } = useParams();
    const [designValues, setDesignValues] = useState({ nameCurrent: 'Cargando...', description: 'Cargando...' })
    const { name, description } = designValues;
    const handleChangeInputs = ({ target }) => {
        setDesignValues({
            ...designValues,
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

    useEffect(() => {
        fetchApi(`designs.php?idDesign=${idOfDesign}`)
            .then(res => {
                console.log(res)
                setDesignValues({
                    nameCurrent: res.name,
                    description: res.description
                })
            })
            .catch(err => console.error(err))
    }, [])

    const handleSubmit = async (e) => {
        e.preventDefault();
        setLoading(true);
        try {
            const resp = await fetchApi(`designs.php?idDesign=${idOfDesign}`, 'PATCH', designValues);
            
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

    return (
        <ContainerBase>
            <section className='container_section flex-column-center-xy generals-layout-edit'>
                <form onSubmit={handleSubmit} autoComplete="off" >
                    <h2>Editar diseños</h2>
                    <label htmlFor="">Nombre</label>
                    <input
                        type="text"
                        className='input-form'
                        required
                        value={name}
                        name='name'
                        onChange={handleChangeInputs}
                    />

                    <label htmlFor="">Descripcion</label>
                    <input
                        type="text"
                        className='input-form'
                        required
                        value={description}
                        name='description'
                        onChange={handleChangeInputs}
                    />

                    <button
                        className={`button-form ${loading && 'opacity'}`}
                        disabled={loading}
                    >{loading ? 'Cargando...' : 'EDITAR DISEÑO'}</button>
                    {
                        error.showMessage &&
                        <span className={`${error.error ? 'warning-message' : 'successfully-message'} `} >{error.message}</span>
                    }
                </form>
            </section>
        </ContainerBase>
    )
}

export default EditDesign;