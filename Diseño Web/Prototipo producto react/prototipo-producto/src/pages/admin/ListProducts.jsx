import { faCheck, faPencil, faTrash } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import React, { Fragment, useEffect } from 'react'
import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { fetchApi } from '../../API/api';
import ContainerBase from '../../components/admin/ContainerBase';

const ListProducts = () => {

    const navigate = useNavigate();
    const [products, setProducts] = useState([]);
    const [modelsOfProduct, setModelsOfProduct] = useState({ idProduct: null, models: [] });
    const [loadingFlags, setLoadingFlags] = useState({ fetchingUsers: true });

    useEffect(() => {
        fetchApi('products.php?search=total', 'GET')
            .then(resp => {
                console.log(resp)
                setProducts(resp.result.data)
            })
            .catch(err => {
                alert('Error interno');
                console.log(err);
            })
            .finally(() => setLoadingFlags({ fetchingUsers: false }))
    }, [modelsOfProduct])

    const handleGetModelsOfProduct = async (idProduct) => {
        const resp = await fetchApi(`products.php?idProductAll=${idProduct}`)
        console.log(resp)
        setModelsOfProduct({ idProduct, models: resp.result.data.models })
    }

    const handleDisableModel = async (barcode, i) => {
        try {
            const resp = await fetchApi(`products.php?barcode=${barcode}&actionMin=disable`, 'PATCH');
            console.log(resp)
            if(resp.status === 'successfully'){
                const modelsMapped = modelsOfProduct.models.map( model => {
                    if(model.barcode === barcode){
                        model.state = 0;
                    }
                    return model;
                })
                setModelsOfProduct(modelsMapped);
            }
        } catch (error) {
            console.error(error);
        }
    }

    const handleEnableModel = async (barcode, i) => {
        try {
            const resp = await fetchApi(`products.php?barcode=${barcode}&actionMin=active`, 'PATCH');
            console.log(resp)
            if(resp.status === 'successfully'){
                const modelsMapped = modelsOfProduct.models.map( model => {
                    if(model.barcode === barcode){
                        model.state = 1;
                    }
                    return model;
                })
                setModelsOfProduct(modelsMapped);
            }
        } catch (error) {
            console.error(error);
        }
    }

    return (
        <ContainerBase>
            <section className='container_section list-products'>
                {
                    loadingFlags.fetchingUsers
                        ? <span className='fetching-data-message'>Obteniendo productos ...</span>
                        : <>
                            <h1 className='title-page'>Productos ingresados</h1>
                            <table className='table-template'>
                                <tbody>
                                    <tr>
                                        <th>Id del producto</th>
                                        <th>Nombre</th>
                                        <th>Descripcion</th>
                                        <th>Categoria</th>
                                        <th>Precio unitario</th>
                                        <th colSpan={2}>Controles</th>
                                    </tr>
                                    {
                                        products.map(product => (
                                            <Fragment key={product.id_product}>
                                                <tr className='row-selectable' onClick={() => handleGetModelsOfProduct(product.id_product)} >
                                                    <td>{product.id_product}</td>
                                                    <td>{product.name}</td>
                                                    <td>{product.description}</td>
                                                    <td>{product.category || 'categoria'}</td>
                                                    <td>{product.price}</td>
                                                    <td className="controls-table" onClick={() => alert('editar producto')} ><FontAwesomeIcon icon={faPencil} /></td>
                                                    <td className="controls-table" onClick={() => alert('eliminar producto')}><FontAwesomeIcon icon={faTrash} /></td>
                                                </tr>
                                                {
                                                    (modelsOfProduct.idProduct === product.id_product) &&
                                                    <>
                                                        <tr className='row-header-child'>
                                                            <th>Codigo de barras</th>
                                                            <th>Talle</th>
                                                            <th>Diseño</th>
                                                            <th>Stock</th>
                                                            <th>Estado</th>
                                                            <th colSpan={2}>Controles</th>
                                                        </tr>
                                                        {
                                                            modelsOfProduct.models.map( model => (
                                                                <tr key={model.barcode} className='row-child'>
                                                                    <td>{model.barcode}</td>
                                                                    <td>{model.size}</td>
                                                                    <td>{model.design}</td>
                                                                    <td>{model.stock} </td>
                                                                    <td>{model.state}</td>
                                                                    {
                                                                        model.state === "1"
                                                                        ? <td className="controls-row-child" onClick={ () => handleDisableModel(model.barcode)} > <FontAwesomeIcon icon={faTrash} /> </td>
                                                                        : <td className="controls-row-child" onClick={ () => handleEnableModel(model.barcode)} > <FontAwesomeIcon icon={faCheck} /> </td>
                                                                    }
                                                                    <td className="controls-row-child" onClick={() => navigate(`/admin/products/edit-model/${model.barcode}`)}> <FontAwesomeIcon icon={faPencil} /> </td>
                                                                </tr>
                                                            ))
                                                        }
                                                   
                                                    </>
                                                }
                                            </Fragment>
                                        ))
                                    }
                                </tbody>
                            </table>
                        </>
                }

            </section>
        </ContainerBase>
    )
}

export default ListProducts;
