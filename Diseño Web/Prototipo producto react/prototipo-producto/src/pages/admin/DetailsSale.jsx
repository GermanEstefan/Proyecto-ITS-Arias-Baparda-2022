
import React, { useState } from "react";
import { useEffect } from "react";
import { useParams } from "react-router-dom";
import { fetchApi } from "../../API/api";
import ContainerBase from "../../components/admin/ContainerBase";

const DetailsSales = () => {

    const { idSale } = useParams();
    const [saleDetail, setSaleDetail] = useState({});
    const [states, setStates] = useState([]);

    useEffect(() => {
        const statesPromise = fetchApi('status.php', 'GET');
        const saleDetailPromise = fetchApi(`sales.php?saleDetail=${idSale}`, 'GET')
        Promise.all([statesPromise, saleDetailPromise])
            .then( ([states, saleDetail]) => {
                console.log(states)
                console.log(saleDetail)
            })
            .catch(err => console.error(err))
    }, [])

    const handleGetHistoryOfSale = async () => {
        const resp = await fetchApi(`sales.php?reportHistory=${idSale}`, 'GET');
        console.log(resp)
    }

    const handleChangeState = (e) => {
        e.preventDefault();

    }

    return (
        <ContainerBase>
            <section className='container_section sale-detail flex-column-center-xy'>

                <div className="sale-detail-content">
                    <div className="sale-detail-content_info-card">
                        <span>Estado actual: <strong> PENDIENTE </strong> </span>
                        <div>
                            <strong>Info venta</strong>
                            <ul>
                                <li> <strong>Total:</strong> 231 </li>
                                <li> <strong>Forma de pago:</strong> PAYPAL </li>
                                <li> <strong>Horario de entrega:</strong> 10:20 </li>
                                <li> <strong>Direccion:</strong> A la vuelta jaja </li>
                            </ul>
                        </div>
                        <div>
                            <strong>Info cliente</strong>
                            <ul>
                                <li> <strong>RUT|DOC:</strong> 1231 </li>
                                <li> <strong>ID cliente:</strong> 11 </li>
                                <li> <strong>Nombre|razon-soc:</strong> PETEROS </li>
                            </ul>
                        </div>
                        <div>
                            <strong>Info venta</strong>
                            <ul>
                                <li>PROD1</li>
                                <li>PROD2</li>
                                <li>PROD3</li>
                            </ul>
                        </div>
                    </div>

                    <div>

                        <form onSubmit={handleChangeState} >
                            <select>
                                {
                                    states.map(state => (
                                        (state.id_status !== 1) &&
                                        <option key={state.id_status} value={state.id_status}>{state.name}</option>
                                    ))
                                }
                            </select>
                            <button>Cambiar estado</button>
                        </form>

                        <button>Ver historial de venta</button>

                    </div>
                </div>

            </section>
        </ContainerBase>
    )
}

export default DetailsSales;