import { faPlusCircle, faXmark } from "@fortawesome/free-solid-svg-icons"
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome"
import React from "react"
import { useState } from "react"
import { useEffect } from "react"
import { useNavigate } from "react-router-dom"
import { fetchApi } from "../../API/api"
import ContainerBase from "../../components/admin/ContainerBase"
import { useForm } from "../../hooks/useForm"

const CreatePromotion = () => {

    const navigate = useNavigate();
    const [products, setProducts] = useState([]);

    useEffect(() => {
        fetchApi('products.php?BOProducts', 'GET')
            .then(res => {
                console.log(res)
                setProducts(res.result.data)
            })
            .catch(err => console.log(err))
    }, [])

    const initStatePromoGeneralValues = {
        idProduct: '',
        name: '',
        stock: '',
        price: '',
        description: '',
        contains: []
    }
    const [promoGeneralValues, handleValuesChange, resetValues] = useForm(initStatePromoGeneralValues);
    const { idProduct, name, stock, price, description } = promoGeneralValues;

    const initStateContainsPromo = [{ quantity: '', haveProduct: '' }];
    const [containsPromo, setContainsPromo] = useState(initStateContainsPromo);
    const handleAddNewContainPromo = () => setContainsPromo([...containsPromo, { quantity: '', haveProduct: '' }]);
    const handleDeleteContainPromo = (i) => {
        const arrayCopy = [...containsPromo]
        arrayCopy.splice(i, 1)
        setContainsPromo(arrayCopy)
    }
    const handleChangeContainPromo = ({ target }, i) => {
        const arrayModificado = [...containsPromo]
        containsPromo[i][target.name] = target.value;
        setContainsPromo(arrayModificado)
    }

    const initStateLoading = {
        showMessage: false,
        message: '',
        error: false
    };
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState(initStateLoading);

    const handleSubmit = async (e) => {
        e.preventDefault();
        const bodyOfRequest = { ...promoGeneralValues, contains: containsPromo }
        setLoading(true);
        try {
            const resp = await fetchApi('products.php?type=promo', 'POST', bodyOfRequest);
            console.log(resp)
            if (resp.status === 'error') {
                setError({ showMessage: true, message: resp.result.error_msg, error: true });
                return setTimeout(() => setError(initStateLoading), 3000)
            }
            setError({ showMessage: true, message: resp.result.msg, error: false });
            resetValues();
            setContainsPromo(initStateContainsPromo);
            return setTimeout(() => setError(initStateLoading), 3000)
        } catch (error) {
            console.error(error);
        } finally {
            setLoading(false)
        }
    }

    return (
        <ContainerBase>
            <section className='container_section create-promo'>

                <h1 className='title-promo-product'>Crear promocion</h1>
                <p className='text-promo-product'>Completar todo los campos</p>

                <div>
                    <ul className = "switch-form">
                        <li className='product' onClick={() => navigate('/admin/products/create')} >PRODUCTO</li>
                        <li className='promo' onClick={() => navigate('/admin/products-promo/create')} >PROMO</li>
                    </ul>

                    <form onSubmit={handleSubmit} className='flex-column-center-xy' autoComplete="off">

                        <div className='form-row-two-columns-with-label'>
                            <div>
                                <label htmlFor="" className='label-form'>Nro de promo</label>
                                <input
                                    type="number"
                                    name="idProduct"
                                    onChange={handleValuesChange}
                                    value={idProduct}
                                    className='input-form'
                                    required
                                />
                            </div>
                            <div>
                                <label htmlFor="" className='label-form'>Nombre</label>
                                <input
                                    type="text"
                                    name="name"
                                    onChange={handleValuesChange}
                                    value={name}
                                    className='input-form'
                                    required
                                />
                            </div>
                        </div>

                        <div className='form-row-two-columns-with-label'>
                            <div>
                                <label htmlFor="" className='label-form'>Precio unitarios</label>
                                <input
                                    type="number"
                                    name="price"
                                    onChange={handleValuesChange}
                                    value={price}
                                    className='input-form'
                                    required
                                />
                            </div>
                            <div>
                                <label htmlFor="" className='label-form'>Unidades de promocion</label>
                                <input
                                    type="number"
                                    name="stock"
                                    onChange={handleValuesChange}
                                    value={stock}
                                    className='input-form'
                                    required
                                />
                            </div>
                        </div>

                        <div className='promo-models-container'>
                            <FontAwesomeIcon className="add-lines" icon={faPlusCircle} onClick={handleAddNewContainPromo} />
                            {
                                containsPromo.map((contain, i) => (
                                    <div key={i} className='form-row-two-columns-with-label'>
                                        <div>
                                            <label htmlFor="" className='label-form'>Lista de productos</label>
                                            <select 
                                                name="haveProduct" 
                                                value={contain.haveProduct} 
                                                onChange={(e) => handleChangeContainPromo(e, i)}
                                                className='select-form' 
                                            >
                                                <option value="" selected disabled>Seleccione</option>
                                                {
                                                    products.map(product => (
                                                        <option key={product.barcode} value={product.barcode}> {`${product.name} - ${product.design} - ${product.size}`} </option>
                                                    ))
                                                }
                                            </select>
                                        </div>
                                        <div>
                                            <label htmlFor="" className='label-form'>Cantidad</label>
                                            <input type="text" className='input-form' name="quantity" value={contain.quantity} onChange={(e) => handleChangeContainPromo(e, i)} />
                                        </div>
                                        {(i !== 0) && <FontAwesomeIcon className="remove-lines" onClick={() => handleDeleteContainPromo(i)} icon={faXmark} />}
                                    </div>
                                ))
                            }
                        </div>

                        <div className="txtarea-container">
                            <label htmlFor="" className='label-form'>Descripcion</label>
                            <textarea
                                name="description"
                                onChange={handleValuesChange}
                                value={description}
                                className='textarea-form'
                            ></textarea>
                        </div>

                        <button
                            className={`button-form ${loading && 'opacity'}`}
                            disabled={loading}
                            type="submit"
                        >{loading ? 'CARGANDO...' : 'CREAR PROMOCION'}</button>
                        <br />
                        {
                            error.showMessage &&
                            <span className={`${error.error ? 'warning-message' : 'successfully-message'} `} >{error.message}</span>
                        }

                    </form>
                </div>
                    
            </section>
        </ContainerBase>
    )
}

export default CreatePromotion