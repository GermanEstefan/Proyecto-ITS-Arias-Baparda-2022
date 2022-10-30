
import React, { useState, useEffect } from "react";
import { fetchApi } from "../../API/api";
import ContainerBase from "../../components/admin/ContainerBase";

const DetailBuys = () => {

    const [detailsBuy, setDetailsBuy] = useState();

    useEffect(() => {
        fetchApi('supply.php?AllSupply', 'GET')
            .then(res => {
                console.log(res)
                const detailsBuyData = res.result.data;
                setDetailsBuy(detailsBuyData);
            })
            .catch(err => console.error(err))
    }, [])

    return (
        <ContainerBase>
            <section className="container_section detail-buys flex-column-center-xy">

            </section>
        </ContainerBase>
    )
}

export default DetailBuys;