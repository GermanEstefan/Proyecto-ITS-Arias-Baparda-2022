
import React, { useState, useEffect } from "react";
import { useParams } from "react-router-dom";
import { fetchApi } from "../../API/api";
import ContainerBase from "../../components/admin/ContainerBase";

const EditCategory = () => {

    const { idOfCategory } = useParams();
    const [categoryValues, setCategoryValues] = useState({ nameCurrent: 'Cargando...', description: 'Cargando...' })
    const { name, description } = categoryValues;
    const handleChangeInputs = ({ target }) => {
        setCategoryValues({
            ...categoryValues,
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
        fetchApi(`categorys.php?idSize=${idOfCategory}`, 'GET')
            .then(res => {
                console.log(res)
                setCategoryValues({
                    nameCurrent: res.result.data[0].name,
                    description: res.result.data[0].description
                })
            })
            .catch(err => console.error(err))
    }, [])

    const handleSubmit = async (e) => {
        e.preventDefault();
        setLoading(true);
        try {
            const categoryPhotoHardcoded = 'sadasd';
            const bodyOfReq = {...categoryValues, picture: categoryPhotoHardcoded}
            const resp = await fetchApi(`categorys.php?idCategory=${idOfCategory}`, 'PATCH', bodyOfReq);
            console.log(resp);
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
                    <h2>Editar categoria</h2>
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
                    >{loading ? 'Cargando...' : 'EDITAR CATEGORIA'}</button>
                    {
                        error.showMessage &&
                        <span className={`${error.error ? 'warning-message' : 'successfully-message'} `} >{error.message}</span>
                    }
                </form>
            </section>
        </ContainerBase>
    )
}

export default EditCategory;