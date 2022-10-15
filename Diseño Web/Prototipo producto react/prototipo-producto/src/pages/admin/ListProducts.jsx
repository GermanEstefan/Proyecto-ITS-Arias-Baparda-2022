import React from 'react'
import { useState } from 'react';
import ContainerBase from '../../components/admin/ContainerBase';

const ListProducts = () => {

    const [products, setProducts] = useState([]);

    return (
        <ContainerBase>
            <section>
                <h1>Listar productos</h1>

            </section>
        </ContainerBase>
    )
}

export default ListProducts;
