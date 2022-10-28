
import React, { useState, useEffect } from "react";
import { useParams } from "react-router-dom";
import { fetchApi } from "../../API/api";
import ContainerBase from "../../components/admin/ContainerBase";
import imgToBase64 from "../../helpers/imgToBase64";

const EditCategory = () => {

    const { idOfCategory } = useParams();
    const [categoryValues, setCategoryValues] = useState({ name: '', description: '', picture: '' })
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
        fetchApi(`categorys.php?idCategory=${idOfCategory}`, 'GET')
            .then(res => {
                console.log(res)
                const categoryData = res.result.data
                setCategoryValues({
                    name: categoryData.name,
                    description: categoryData.description,
                    picture: categoryData.picture
                })
            })
            .catch(err => console.error(err))
    }, [])

    const handleSubmit = async (e) => {
        e.preventDefault();
        setLoading(true);
        let bodyOfRequest = {...categoryValues};
        const imgProdEdit = document.getElementById('img-cat-edit');
        try {
            if(imgProdEdit.value){
                const base64Img = await imgToBase64(imgProdEdit.files[0]);
                bodyOfRequest = {...categoryValues, picture: base64Img};       
            } 
            const resp = await fetchApi(`categorys.php?idCategory=${idOfCategory}`, 'PATCH', bodyOfRequest);
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
                    <label className="label-form">Nombre</label>
                    <input
                        type="text"
                        className='input-form'
                        required
                        value={name}
                        name='name'
                        onChange={handleChangeInputs}
                    />

                    <label className="label-form">Descripcion</label>
                    <input
                        type="text"
                        className='input-form'
                        required
                        value={description}
                        name='description'
                        onChange={handleChangeInputs}
                    />
                    <label className="label-form">Imagen</label>
                    <input type="file" id="img-cat-edit" />
                    <img src={categoryValues.picture} alt={'img'} />
                    <br />
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