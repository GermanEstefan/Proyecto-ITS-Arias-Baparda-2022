import React from "react";
import { useState, useEffect } from "react";
import { useParams } from "react-router-dom";
import { fetchApi } from "../../API/api";
import ContainerBase from "../../components/admin/ContainerBase";

const EditModelProduct = () => {

    const { barcode } = useParams();
    const [values, setValues] = useState({ stock: '', prodDesign: '', prodSize: '', nameCurrent: '' })
    const { stock, prodDesign, prodSize, nameCurrent } = values;
    const handleChangeInputs = ({ target }) => {
        setValues({
            ...values,
            [target.name]: target.value
        })
    }
    const [selectValues, setSelectValues] = useState({ sizes: [], designs: [] });

    useEffect(() => {
        const productPromise = fetchApi(`products.php?barcode=${barcode}`, 'GET');
        const sizesPromise = fetchApi('sizes.php', 'GET');
        const designsPromise = fetchApi('designs.php', 'GET');
        Promise.all([productPromise, sizesPromise, designsPromise])
            .then(([product, sizes, designs]) => {
                const { design, stock, size, name } = product.result.data;
                console.log(product.result)
                setValues({ stock: stock, prodDesign: design, prodSize: size, nameCurrent: name })
                setSelectValues({ sizes: sizes.result.data, designs: designs.result.data });
            })
    }, [])

    return (
        <ContainerBase>
            <section>
                <form action="">

                    <div>
                        <label>Name</label>
                        <input
                            type="text"
                            name="name"
                            onChange={handleChangeInputs}
                            value={nameCurrent}
                            readOnly
                        />
                    </div>

                    <div >
                        <label htmlFor="" className='label-form'>Talle</label>
                        <select
                            required
                            className='select-form'
                            name='prodSize'
                            onChange={handleChangeInputs}
                            value={prodSize}
                        >
                            {
                                selectValues.sizes.map(size => (
                                    <option key={size.id_size} selected = { (size.name === size) ? true : false} value={size.id_size}>{size.name}</option>
                                ))
                            }
                        </select>
                    </div>

                    <div>
                        <label htmlFor="" className='label-form'>Diseño</label>
                        <select
                            required
                            className='select-form'
                            onChange={handleChangeInputs}
                            name='prodDesign'
                            value={prodDesign}
                        >
                            {
                                selectValues.designs.map(design => (
                                    <option value={design.id_design} selected = { (design.name === prodDesign) ? true : false } key={design.id_design}>{design.name}</option>
                                ))
                            }
                        </select>
                    </div>

                    <div>
                        <label htmlFor="" className='label-form'>Stock</label>
                        <input required type="text" onChange={handleChangeInputs} className='input-form' name='stock' value={stock} />
                    </div>
                </form>
            </section>
        </ContainerBase>
    )
}

export default EditModelProduct;