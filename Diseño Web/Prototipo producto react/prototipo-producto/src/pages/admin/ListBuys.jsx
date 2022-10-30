
import React, { useEffect } from "react";
import { useState } from "react";
import { useNavigate } from "react-router-dom";
import { fetchApi } from "../../API/api";
import ContainerBase from "../../components/admin/ContainerBase";

const ListBuys = () => {

    const navigate = useNavigate();
    const [supply, setSupply] = useState({});

    useEffect(() => {
        fetchApi('supply.php?AllSupply', 'GET')
            .then(res => {
                console.log(res)
                const supplyData = res.result.data;
                setSupply(supplyData);
            })
            .catch(err => console.error(err))
    }, [])

    return (
        <ContainerBase>
            <section className='container_section list-buys flex-column-center-xy'>
                <h1>Informacion historica sobre las compras realizadas</h1>
                <div className="list-buys_general-info" >
                    <span>Total de compras efectuadas: <strong> {supply.TotalSupply} </strong> </span>
                    <span>Total de gastos: <strong> {supply.totalSpent} </strong> </span>
                </div>
                <table className='table-template'>
                    <caption>Compras</caption>
                    <tbody>
                        <tr>
                            <th>ID</th>
                            <th>Fecha</th>
                            <th>CI empleado</th>
                            <th>Proveedor</th>
                            <th>Total compras</th>
                            <th>Controles</th>
                        </tr>
                        {
                            supply.supplys && supply.supplys.map(supply => (
                                <tr key={supply.idSupply} >
                                    <td>{supply.idSupply}</td>
                                    <td>{supply.date}</td>
                                    <td>{`${supply.ciEmployee}`}</td>
                                    <td>{supply.nameSupplier}</td>
                                    <td>{supply.totalSupply}</td>
                                    <td className="table-control-text" onClick={() => navigate(`/admin/products/edit-product/${supply.id_product}`)}> <span>Ver detalle</span> </td>
                                </tr>
                            ))
                        }
                    </tbody>
                </table>

            </section>
        </ContainerBase>
    )
}

export default ListBuys;