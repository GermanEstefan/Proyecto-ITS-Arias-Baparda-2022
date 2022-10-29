
import React, { useEffect, useState } from "react"
import { useParams } from "react-router-dom";
import { fetchApi } from "../../API/api";
import ContainerBase from "../../components/admin/ContainerBase";
import imgToBase64 from "../../helpers/imgToBase64";

const EditPromo = () => {

    const { idPromo } = useParams();
    const [values, setValues] = useState({ stock: '', name: '', price: '', description: '', picture: '' })
    const { stock, name, price, description, picture } = values;
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
        fetchApi(`products.php??BOproductsOfPromo=${idPromo}}`)
            .then(res => {
                const productData = res.result.data;
                console.log(productData)
                setValues({
                    name: productData.namePromo,
                    stock: productData.stockPromo,
                    price: productData.pricePromo,
                    description: productData.descriptionPromo,
                    state: productData.state
                })
            })
            .catch(err => console.error(err))
    }, [])

    const handleEditPromo = async (e) => {
        e.preventDefault();
        let bodyOfRequest = { ...values };
        const imgPromoEdit = document.getElementById('img-promo-edit');
        try {
            let base64Img;
            if (imgPromoEdit.value) {
                base64Img = await imgToBase64(imgPromoEdit.files[0]);
                bodyOfRequest = { ...values, picture: base64Img };
            }
            const resp = await fetchApi(`products.php?idProduct=${idPromo}&actionMax=editPromo`, 'PATCH', bodyOfRequest);
            console.log(resp);
            if (resp.status === 'error') {
                setError({ showMessage: true, message: resp.result.error_msg, error: true });
                return setTimeout(() => setError(initStateLoading), 3000)
            }
            setValues({ ...values, picture: base64Img });
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
                <form onSubmit={handleEditPromo}>
                    <h2>Editar promo</h2>
                    <label className="label-form">ID</label>
                    <input
                        type="text"
                        value={idPromo}
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

                    <label className="label-form">Imagen</label>
                    <input type="file" id="img-promo-edit" />
                    <br />
                    <img src={values.picture} alt="img" />
                    <br />
                    <label className="label-form">Stock</label>
                    <input
                        type="text"
                        onChange={handleChangeInputs}
                        name='stock'
                        value={stock}
                        className="input-form"
                    />

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
                    >{loading ? 'Cargando...' : 'EDITAR PROMO'}</button>
                    {
                        error.showMessage &&
                        <span className={`${error.error ? 'warning-message' : 'successfully-message'} `} >{error.message}</span>
                    }
                </form>
            </section>
        </ContainerBase>
    )
}

export default EditPromo;