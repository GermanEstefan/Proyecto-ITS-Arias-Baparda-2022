import React from "react";
import { useState } from "react";
import { useEffect } from "react";
import { useParams } from "react-router-dom";
import { fetchApi } from "../../API/api";
import ContainerBase from "../../components/admin/ContainerBase";
import imgToBase64 from "../../helpers/imgToBase64";

const EditProducts = () => {

    const { idProduct } = useParams();
    const [categorys, setCategorys] = useState([]);
    const [values, setValues] = useState({ prodCategory: '', description: '', name: '', price: '', picture: '' })
    const { prodCategory, description, name, price } = values;
    const handleChangeInputs = ({ target }) => {
        setValues({
            ...values,
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
        const productPromise = fetchApi(`products.php?BOmodelsOfProduct=${idProduct}`);
        const categorysPromise = fetchApi('categorys.php', 'GET');
        Promise.all([productPromise, categorysPromise])
            .then(([product, categorys]) => {
                const productData = product.result.data;
                setValues({
                    prodCategory: productData.category,
                    description: productData.description,
                    name: productData.name,
                    price: productData.price,
                    picture: productData.picture
                })
                console.log(categorys.result.data)
                console.log(productData)
                setCategorys(categorys.result.data)
            })
            .catch(err => console.error(err))
    }, [])

    const handleEditProduct = async (e) => {
        e.preventDefault();
        let bodyOfRequest = {...values};
        const imgProdEdit = document.getElementById('img-prod-edit');
        try {
            if(imgProdEdit.value){
                const base64Img = await imgToBase64(imgProdEdit.files[0]);
                bodyOfRequest = {...values, picture: base64Img};       
            } 
            const resp = await fetchApi(`products.php?idProduct=${idProduct}&actionMax=edit`, 'PATCH', bodyOfRequest);
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
            <section className="container_section flex-column-center-xy generals-layout-edit">
                <form onSubmit={handleEditProduct}>
                    <h2>Editar producto</h2>
                    <label className="label-form">ID</label>
                    <input
                        type="text"
                        value={idProduct}
                        readOnly
                        className="input-form opacity"
                    />

                    <label className="label-form">Nombre</label>
                    <input
                        type="text"
                        onChange={handleChangeInputs}
                        name='name'
                        value={name}
                        className="input-form"
                    />

                    <label className="label-form">Precio</label>
                    <input
                        type="text"
                        onChange={handleChangeInputs}
                        name='price'
                        value={price}
                        className="input-form"
                    />

                    <label className="label-form">Categoria</label>
                    <select
                        name="category"
                        onChange={handleChangeInputs}
                        value={prodCategory}
                        className='select-form'
                    >
                        {categorys.map(category => <option value={category.id_category}>{category.name}</option>)}
                    </select>

                    <label className="label-form">Imagen</label>
                    <input type="file" id="img-prod-edit" />
                    <img src={values.picture} alt="img" />

                    <label className="label-form">Descripcion</label>
                    <textarea
                        onChange={handleChangeInputs}
                        name='description'
                        value={description}
                        className="textarea-form"
                    ></textarea>

                    <button
                        className={`button-form ${loading && 'opacity'}`}
                        disabled={loading}
                    >{loading ? 'Cargando...' : 'EDITAR PRODUCTO'}</button>
                    {
                        error.showMessage &&
                        <span className={`${error.error ? 'warning-message' : 'successfully-message'} `} >{error.message}</span>
                    }

                </form>
            </section>
        </ContainerBase>
    )
}

export default EditProducts;