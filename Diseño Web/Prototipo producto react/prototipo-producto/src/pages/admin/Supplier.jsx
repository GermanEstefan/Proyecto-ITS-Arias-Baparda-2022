
import { faCheck, faPencil, faTrash } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import React, { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import { fetchApi } from "../../API/api";
import ContainerBase from "../../components/admin/ContainerBase";
import { useForm } from "../../hooks/useForm";

const Supplier = () => {

    const navigate = useNavigate();

    const [values, handleChangeValues, resetForm] = useForm({ rut: '', companyName: '', address: '', phone: '' });
    const { rut, companyName, address, phone } = values;

    const initStateLoading = {
        showMessage: false,
        message: '',
        error: false
    };
    const [loadingFlags, setLoadingFlags] = useState({ createSupplier: false, fetchingSuppliers: true });
    const [error, setError] = useState(initStateLoading);

    const [suppliers, setSuppliers] = useState([]);

    const handleCreateSupplier = async (e) => {
        e.preventDefault();
        setLoadingFlags({ ...loadingFlags, createSupplier: true });
        try {
            const resp = await fetchApi('suppliers.php', 'POST', values);
            console.log(resp)
            if (resp.status === 'error') {
                setError({ showMessage: true, message: resp.result.error_msg, error: true });
                return setTimeout(() => setError(initStateLoading), 3000)
            }
            const lastIdOfSupplier = parseInt(suppliers[suppliers.length - 1 ].id_supplier) + 1
            setSuppliers([...suppliers, {rut: values.rut, company_name: values.companyName, address: values.address, phone: values.phone, id_supplier: lastIdOfSupplier, state : '1' }])
            setError({ showMessage: true, message: resp.result.msg, error: false });
            resetForm();
            return setTimeout(() => setError(initStateLoading), 3000)
        } catch (error) {
            alert('Internal error');
            console.log(error);
        } finally {
            setLoadingFlags({ ...loadingFlags, createSupplier: false });
        }
    }

    const handleDeleteSupply = async (idSupplier) => {
        const confirm = window.confirm('¿Estas seguro que desas borrar el proveedor?')
        if(!confirm) return;
        const resp = await fetchApi(`suppliers.php?idSupplier=${idSupplier}&action=disable `, 'PATCH');
        console.log(resp)
        if(resp.status === 'error'){
            return alert(resp.result.error_msg)
        }
        const suppliersFiltered = suppliers.filter( supplier => {
            if(supplier.id_supplier === idSupplier){
                supplier.state = '0';
            }
            return supplier
        });
        return setSuppliers(suppliersFiltered);
    }

    const handleEnableSupply = async (idSupplier) => {
        const confirm = window.confirm('¿Estas seguro que desas activar el proveedor?')
        if(!confirm) return;
        const resp = await fetchApi(`suppliers.php?idSupplier=${idSupplier}&action=active`, 'PATCH');
        console.log(resp)
        if(resp.status === 'error'){
            return alert(resp.result.error_msg)
        }
        const suppliersFiltered = suppliers.filter( supplier => {
            if(supplier.id_supplier === idSupplier){
                supplier.state = '1';
            }
            return supplier
        });
        return setSuppliers(suppliersFiltered);
    }

    useEffect(() => {
        fetchApi('suppliers.php?all', 'GET')
            .then(res => {
                setSuppliers(res.result.data)
            })
            .catch(err => console.error(err))
            .finally(() => setLoadingFlags({ ...loadingFlags, fetchingSuppliers: false }))
    }, [])


    return (
        <ContainerBase>
            <section className="container_section supplier generals-layout flex-column-center-xy" >
                {
                    loadingFlags.fetchingSuppliers
                        ? <span className='fetching-data-message'>Obteniendo proveedores...</span>
                        : <>
                            <h1>Proveedores</h1>
                            <div>
                                <form autoComplete="off" onSubmit={handleCreateSupplier}>
                                    <h2>Crear un nuevo proveedor</h2>
                                    <div>
                                        <label htmlFor="">RUT</label>
                                        <input
                                            type="number"
                                            name="rut"
                                            onChange={handleChangeValues}
                                            value={rut}
                                            required
                                            className='input-form'
                                        />
                                    </div>
                                    <div>
                                        <label htmlFor="">Razon social</label>
                                        <input
                                            type="text"
                                            name="companyName"
                                            onChange={handleChangeValues}
                                            value={companyName}
                                            required
                                            className='input-form'
                                        />
                                    </div>
                                    <div>
                                        <label htmlFor="">Direccion</label>
                                        <input
                                            type="text"
                                            name="address"
                                            onChange={handleChangeValues}
                                            value={address}
                                            required
                                            className='input-form'
                                        />
                                    </div>
                                    <div>
                                        <label htmlFor="">Telefono</label>
                                        <input
                                            type="number"
                                            name="phone"
                                            onChange={handleChangeValues}
                                            value={phone}
                                            required
                                            className='input-form'
                                        />
                                    </div>
                                    <button
                                        className={`button-form ${loadingFlags.createSupplier && 'opacity'}`}
                                        disabled={loadingFlags.createSupplier}
                                    >{loadingFlags.createSupplier ? 'Cargando...' : 'CREAR PROVEEDOR'}</button>
                                    {
                                        error.showMessage &&
                                        <span className={`${error.error ? 'warning-message' : 'successfully-message'} `} >{error.message}</span>
                                    }
                                </form>
                            </div>

                            <div>
                                <h2>Lista de proveedores</h2>
                                {
                                    suppliers.length === 0
                                        ? <span>No hay proveedores creados</span>
                                        : <table className='table-template'>
                                            <tbody>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Razon social</th>
                                                    <th>RUT</th>
                                                    <th>Direccion</th>
                                                    <th>Telefono</th>
                                                    <th>Estado</th>
                                                    <th colSpan={2}>Controles</th>
                                                </tr>
                                                {
                                                    suppliers.map(supplier => (
                                                        <tr key={supplier.id_supplier}>
                                                            <td>{supplier.id_supplier}</td>
                                                            <td>{supplier.company_name}</td>
                                                            <td>{supplier.rut}</td>
                                                            <td>{supplier.address}</td>
                                                            <td>{supplier.phone}</td>   
                                                            <td>{supplier.state}</td>                         
                                                            <td className="controls-table" onClick={() => navigate(`/admin/generals/supplier/edit/${supplier.rut}`)}><FontAwesomeIcon icon={faPencil} /></td>
                                                            {
                                                                supplier.state === "1"
                                                                ? <td className="controls-table" onClick={() => handleDeleteSupply(supplier.id_supplier)} ><FontAwesomeIcon icon={faTrash} /></td>
                                                                : <td className="controls-table" title="Activar proveedor" onClick={() => handleEnableSupply(supplier.id_supplier)} ><FontAwesomeIcon icon={faCheck} /></td>
                                                            }
                                                            
                                                        </tr>
                                                    ))
                                                }
                                            </tbody>
                                        </table>
                                }
                            </div>
                        </>
                }
            </section>
        </ContainerBase>
    )
}

export default Supplier;