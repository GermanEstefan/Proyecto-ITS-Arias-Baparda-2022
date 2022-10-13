import { faPlusCircle } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import React from 'react'
import { useEffect } from 'react';
import { useState } from 'react';
import { fetchApi } from '../../API/api';
import ContainerBase from '../../components/admin/ContainerBase';
import { useForm } from '../../hooks/useForm';

const CreateProducts = () => {

    const [valuesForm, setValuesForm] = useState({ categorys: [], sizes: [], designs: [] });
    const { categorys, sizes, designs } = valuesForm

    const initStateProductSlice = {
        idProduct: '',
        name: '',
        prodCategory: '',
        price: '',
        description: '',
        models: []
    }

    const [valuesSliceProduct, handleValuesChangeOfSliceProduct] = useForm(initStateProductSlice);
    const { idProduct, name, prodCategory, price, description } = valuesSliceProduct;

    //const [models, setModels] = useState([{ prodDesign: '', prodSize: '', stock: '' }]);
    const [amountLines, setAmountLines] = useState([{ prodDesign: '', prodSize: '', stock: '' }]);

    useEffect(() => {
        const categorysPromise = fetchApi('categorys.php', 'GET');
        const sizesPromise = fetchApi('sizes.php', 'GET');
        const designsPromise = fetchApi('designs.php', 'GET');
        Promise.all([categorysPromise, sizesPromise, designsPromise])
            .then(([categorysRes, sizesRes, designsRes]) => {
                setValuesForm({
                    categorys: categorysRes.result.data,
                    sizes: sizesRes.result.data,
                    designs: designsRes.result.data
                })
            })
            .catch(err => {
                console.log(err)
                alert('Error interno')
            })
    }, [])

    const handleAddLine = () => {
        setAmountLines([...amountLines, { prodDesign: '', prodSize: '', stock: '' }])
    }
    const handleChangeValueOfLines = ({target}, i) => {
        const arrayModificado = [...amountLines]
        amountLines[i][target.name] = target.value;
        setAmountLines(arrayModificado)
    }

    const handleSubmitProduct = async (e) => {
        e.preventDefault();
        const bodyOfRequest = {...valuesSliceProduct, models: amountLines } 
        try {
            const resp = await fetchApi('products.php', 'POST', bodyOfRequest);
            console.log(resp)
        } catch (error) {
            console.error(error);
        }
        
        
    }

    return (
        <ContainerBase>
            <section className='container_section create-product'>
                <h1>Crear producto</h1>
                <p>Completar todo los campos</p>
                <div>
                    <ul className='create-product_switch'>
                        <li className='create-product_switch_product'>PRODUCTO</li>
                        <li className='create-product_switch_promo'>PROMO</li>
                    </ul>

                    <form className='create-product_product-form' onSubmit={handleSubmitProduct}>

                        <div className='create-product_product-form_row'>
                            <div>
                                <label htmlFor="">Id Producto</label>
                                <input
                                    className='input-form'
                                    type="text"
                                    name='idProduct'
                                    onChange={handleValuesChangeOfSliceProduct}
                                    value={idProduct}
                                />
                            </div>
                            <div>
                                <label htmlFor="">Nombre</label>
                                <input
                                    type="text"
                                    className='input-form'
                                    name='name'
                                    onChange={handleValuesChangeOfSliceProduct}
                                    value={name}
                                />
                            </div>
                        </div>

                        <div className='create-product_product-form_row'>
                            <div>
                                <label htmlFor="">Precio unitario</label>
                                <input
                                    type="text"
                                    className='input-form'
                                    name='price'
                                    onChange={handleValuesChangeOfSliceProduct}
                                    value={price}
                                />
                            </div>
                            <div>
                                <label htmlFor="">Categoria</label>
                                <select
                                    className='select-form'
                                    name='prodCategory'
                                    value={prodCategory}
                                    onChange={handleValuesChangeOfSliceProduct}
                                >
                                    <option value="" selected disabled>Seleccione</option>
                                    {
                                        categorys.map(category => (
                                            <option
                                                key={category.id_category}
                                                value={category.id_category}
                                            >{category.name}</option>
                                        ))
                                    }
                                </select>
                            </div>

                        </div>

                        <hr />
                        <FontAwesomeIcon icon={faPlusCircle} onClick={handleAddLine} />
                        {
                            amountLines.map( (line, i) => (
                                <div className='create-product_product-form_row-3'>

                                <div >
                                    <label htmlFor="">Talle</label>
                                    <select className='select-form' name='prodSize' onChange={ (e) => handleChangeValueOfLines(e,i)} value={line.prodSize} >
                                        <option value="" selected disabled>Seleccione</option>
                                        {
                                            sizes.map(size => (
                                                <option key={size.id_size} value={size.id_size}>{size.name}</option>
                                            ))
                                        }
                                    </select>
                                </div>
    
                                <div>
                                    <label htmlFor="">Diseño</label>
                                    <select className='select-form' onChange={(e) => handleChangeValueOfLines(e,i)} name='prodDesign' value={line.prodDesign}>
                                        <option value="" selected disabled>Seleccione</option>
                                        {
                                            designs.map(design => (
                                                <option value={design.id_design} key={design.id_design}>{design.name}</option>
                                            ))
                                        }
                                    </select>
                                </div>
    
                                <div>
                                    <label htmlFor="">Stock</label>
                                    <input type="text" onChange={(e) => handleChangeValueOfLines(e,i)} className='input-form' name='stock' value={line.stock} />
                                </div>
    
                            </div>
                            ))
                        }

                        <div className='create-product_product-form_txtarea'>
                            <label>Descripcion</label>
                            <textarea
                                className='textarea-form'
                                value={description}
                                onChange={handleValuesChangeOfSliceProduct}
                                name='description'
                            ></textarea>
                        </div>


                        <button className='button-form' type='submit'>CONFIRMAR</button>

                    </form>
                </div>
            </section>
        </ContainerBase>
    )
}

export default CreateProducts;
