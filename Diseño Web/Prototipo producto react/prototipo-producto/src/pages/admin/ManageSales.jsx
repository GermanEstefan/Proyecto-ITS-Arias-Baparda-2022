import React from 'react'
import { useState } from 'react';
import { useEffect } from 'react';
import { fetchApi } from '../../API/api';
import ContainerBase from '../../components/admin/ContainerBase';

const ManageSales = () => {

    const [states, setStates] = useState([]);
    const [sales, setSales] = useState([]);

    useEffect(() => {
        const statesPromise = fetchApi('status.php', 'GET');
        const salesPromise = fetchApi('sales.php?status=pendiente', 'GET');
        Promise.all([statesPromise, salesPromise])
            .then(([states, sales]) => {
                const statesData = states.result.data;
                const salesData = sales.result.data.sales;
                setStates(statesData);
                setSales(salesData);
            })
            .catch(err => console.error(err))
    }, [])

    const handleChangeState = ({ target }) => {
        try {
            const resp = fetchApi(`sales.php?status=${target.value}`, 'GET');
            console.log(resp)
            const salesData = resp.result.data.sales;
            setSales(salesData);
        } catch (error) {
            console.error(error);
        }
    }

    return (
        <ContainerBase>
            <section className='container_section flex-column-center-xy manage-sales'>
                <div className='manage-sales_filters'>
                    <div>
                        <label>Filtrar por fecha</label>
                        <input type="date" />
                    </div>
                    
                    <strong>
                        {
                            !sales.length
                            ? 'No hay ventas registradas por el momento'
                            : (sales.length === 1) ? '1 venta registrada' : `${sales.length + 1} ventas registradas`
                        }
                    </strong>

                    <div>
                        <label>Seleccione un estado</label>
                        <select onChange={handleChangeState}>
                            {
                                states.map(state => (
                                    (state.id_status !== 1) &&
                                    <option value={state.id_status}>{state.name}</option>
                                ))
                            }
                        </select>
                    </div>
                </div>
                <table className='table-template'>
                    <tbody>
                        <tr>
                            <th>ID</th>
                            <th>Estado</th>
                            <th>Empleado</th>
                            <th>Importe</th>
                            <th>Ultima actualizacion</th>
                            <th>Controles</th>
                        </tr>

                        {
                            sales.map(sale => (
                                <tr>
                                    <td>{sale.idSale}</td>
                                    <td>{sale.nameStatus}</td>
                                    <td>{sale.employeeMail}</td>
                                    <td>{sale.totalSale}</td>
                                    <td>{sale.lastUpdate}</td>
                                    <td className="table-control-text">Ver detalle</td>
                                </tr>
                            ))
                        }

                    </tbody>
                </table>
            </section>
        </ContainerBase>
    )
}

export default ManageSales;
