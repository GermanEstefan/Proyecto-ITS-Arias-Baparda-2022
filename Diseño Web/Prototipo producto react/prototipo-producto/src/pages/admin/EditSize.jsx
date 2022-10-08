
import React, { useState, useEffect } from "react";
import { useParams } from "react-router-dom";
import { fetchApi } from "../../API/api";

const EditSize = () => {

    const { idOfSize } = useParams();
    const [sizeValues, setSizeValues] = useState({ name: 'Cargando...', description: 'Cargando...' })
    const { name, description } = sizeValues;
    const handleChangeInputs = ({ target }) => {
        setSizeValues({
            ...sizeValues,
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
        fetchApi(`sizes.php?idSize=${idOfSize}`, 'GET' )
            .then(res => {
                console.log(res)
                setSizeValues({
                    name: res.name,
                    description: res.description
                })
            })
            .catch(err => console.error(err))
    }, [])

    const handleSubmit = async (e) => {
        e.preventDefault();
        setLoading(true);
        try {
            const resp = await fetchApi(`sizes.php?idSize=${idOfSize}`, 'PUT', sizeValues);
            console.log(resp);
            if(resp.status === 'error'){
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
        <section className='container_section flex-column-center-xy'>
            <form onSubmit={handleSubmit} autoComplete="off" >

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
                >{loading ? 'Cargando...' : 'EDITAR TALLE'}</button>
                {
                    error.showMessage &&
                    <span className={`${error.error ? 'warning-message' : 'successfully-message'} `} >{error.message}</span>
                }
            </form>
        </section>
    )
}

export default EditSize;