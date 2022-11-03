import React from 'react'
import { useState } from 'react';
import { useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { fetchApi } from '../../API/api';
import ContainerBase from '../../components/admin/ContainerBase';

const ManageSales = () => {

    const navigate = useNavigate();
    const [states, setStates] = useState([]);
    const [sales, setSales] = useState([]);

    useEffect(() => {
        const statesPromise = fetchApi('status.php', 'GET');
        const salesPromise = fetchApi('sales.php?status=pendiente', 'GET');
        Promise.all([statesPromise, salesPromise])
            .then(([states, sales]) => {
                const statesData = states.result.data;
                const salesData = sales.result.data;
                setStates(statesData);
                setSales(salesData);
            })
            .catch(err => console.error(err))
    }, [])

    const handleChangeState = async ({ target }) => {
        try {
            const resp = await fetchApi(`sales.php?status=${target.value}`, 'GET');
            console.log(resp)
            const salesData = resp.result.data;
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
                            !sales
                                ? 'No hay ventas registradas por el momento'
                                : (sales.length === 1) ? '1 venta registrada' : `${sales.length} ventas registradas`
                        }
                    </strong>

                    <div>
                        <label>Seleccione un estado</label>
                        <select onChange={handleChangeState}>
                            {
                                states.map(state => (
                                    (state.id_status !== 1) &&
                                    <option selected={state.name === 'PENDIENTE' && true} key={state.id_status} value={state.name}>{state.name}</option>
                                ))
                            }
                        </select>
                    </div>
                </div>
                {
                    !(sales.length)
                        ? <h2>No hay ventas registradas</h2>
                        : <table className='table-template'>
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
                                        <tr key={sale.idSale} >
                                            <td>{sale.idSale}</td>
                                            <td>{sale.nameStatus}</td>
                                            <td>{sale.employeeMail}</td>
                                            <td>{sale.totalSale}</td>
                                            <td>{sale.lastUpdate}</td>
                                            <td className="table-control-text" onClick={() => navigate(`/admin/sales/manage/details/${sale.idSale}`)}><span>Ver detalle</span></td>
                                        </tr>
                                    ))
                                }
                            </tbody>
                        </table>
                }

            </section>
        </ContainerBase>
    )
}

export default ManageSales;
