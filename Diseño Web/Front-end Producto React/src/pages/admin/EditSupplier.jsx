
import React from "react";
import { useState } from "react";
import { useEffect } from "react";
import { useParams } from "react-router-dom";
import { fetchApi } from "../../API/api";
import ContainerBase from "../../components/admin/ContainerBase";

const EditSupplier = () => {

    const { rut: rutParams } = useParams();
    const [supplierValues, setSupplierValues] = useState({
        rut: 'Cargando...',
        companyName: 'Cargando...',
        address: 'Cargando...',
        phone: 'Cargando...',
        idSupplier: ''
    });
    const { rut, companyName, address, phone, idSupplier } = supplierValues;
    const handleChangeInputs = ({ target }) => {
        setSupplierValues({
            ...supplierValues,
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

    const handleEditSupplier = async (e) => {
        e.preventDefault();
        setLoading(true);
        try {
            const resp = await fetchApi(`suppliers.php?idSupplier=${idSupplier}&action=edit`, 'PATCH', supplierValues);
            
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

    useEffect(() => {
        fetchApi(`suppliers.php?rut=${rutParams}`, 'GET')
            .then(res => {
                const values = res.result.data;
                
                setSupplierValues({
                    rut: values.rut,
                    companyName: values.company_name,
                    address: values.address,
                    phone: values.phone,
                    idSupplier: values.id_supplier
                })
            })
            .catch(err => console.error(err))
    }, [])

    return (
        <ContainerBase>
            <section className='container_section generals-layout flex-column-center-xy'>
                <form onSubmit={handleEditSupplier} autoComplete="off" >
                    <h2>Editar proveedor</h2>
                    <label htmlFor="">Razon social</label>
                    <input
                        type="text"
                        className='input-form'
                        required
                        value={companyName}
                        name='companyName'
                        onChange={handleChangeInputs}
                    />

                    <label htmlFor="">RUT</label>
                    <input
                        type="text"
                        className='input-form'
                        required
                        value={rut}
                        name='rut'
                        onChange={handleChangeInputs}
                    />

                    <label htmlFor="">Direccion</label>
                    <input
                        type="text"
                        className='input-form'
                        required
                        value={address}
                        name='address'
                        onChange={handleChangeInputs}
                    />

                    <label htmlFor="">Telefono</label>
                    <input
                        type="text"
                        className='input-form'
                        required
                        value={phone}
                        name='phone'
                        onChange={handleChangeInputs}
                    />

                    <button
                        className={`button-form ${loading && 'opacity'}`}
                        disabled={loading}
                    >{loading ? 'Cargando...' : 'EDITAR PROVEEDOR'}</button>
                    {
                        error.showMessage &&
                        <span className={`${error.error ? 'warning-message' : 'successfully-message'} `} >{error.message}</span>
                    }
                </form>
            </section>
        </ContainerBase>
    )
}

export default EditSupplier;