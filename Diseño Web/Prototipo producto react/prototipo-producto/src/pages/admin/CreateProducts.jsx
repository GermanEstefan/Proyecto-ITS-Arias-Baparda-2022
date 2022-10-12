import { faPlusCircle } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import React from 'react'
import { useEffect } from 'react';
import { useState } from 'react';
import { fetchApi } from '../../API/api';
import ContainerBase from '../../components/admin/ContainerBase';

const CreateProducts = () => {

    const [view, setView] = useState('');
    const [valuesForm, setValuesForm] = useState({ categorys: [], sizes: [], designs: [] });
    const { categorys, sizes, designs } = valuesForm

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

                    <form className='create-product_product-form'>

                        <div className='create-product_product-form_row'>
                            <div>
                                <label htmlFor="">Id Producto</label>
                                <input className='input-form' type="text" />
                            </div>
                            <div>
                                <label htmlFor="">Nombre</label>
                                <input type="text" className='input-form' />
                            </div>
                        </div>

                        <div className='create-product_product-form_row'>
                            <div>
                                <label htmlFor="">Precio unitario</label>
                                <input type="text" className='input-form' />
                            </div>
                            <div>
                                <label htmlFor="">Categoria</label>
                                <select className='select-form'>
                                    {
                                        categorys.map(category => (
                                            <option key={category.id_category} value={category.id_category}>{category.name}</option>
                                        ))
                                    }
                                </select>
                            </div>

                        </div>

                        <hr />

                        <div className='create-product_product-form_row-3'>

                            <div >
                                <label htmlFor="">Talle</label>
                                <select className='select-form'>
                                    {
                                        sizes.map(size => (
                                            <option key={size.id_size} value={size.id_size}>{size.name}</option>
                                        ))
                                    }
                                </select>
                            </div>

                            <div>
                                <label htmlFor="">Diseño</label>
                                <select className='select-form'>
                                    {
                                        designs.map(design => (
                                            <option value={design.id_design} key={design.id_design}>{design.name}</option>
                                        ))
                                    }
                                </select>
                            </div>

                            <div>
                                <label htmlFor="">Stock</label>
                                <input type="text" className='input-form' />
                            </div>

                            <FontAwesomeIcon icon={faPlusCircle} />

                        </div>

                        <div className='create-product_product-form_txtarea'>
                            <label>Descripcion</label>
                            <textarea className='textarea-form'>

                            </textarea>
                        </div>


                        <button className='button-form' type='submit'>CONFIRMAR</button>

                    </form>
                </div>
            </section>
        </ContainerBase>
    )
}

export default CreateProducts;
