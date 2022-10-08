
import React, { useState } from 'react'
import ContainerBase from '../../components/admin/ContainerBase';

const Sizes = () => {

    const [loadingFlags, setLoadingFlags] = useState({ createCategory: false, fetchingCategorys: true });

    return (
        <ContainerBase>
            <section className='container_section flex-column-center-xy'>
                <h1>Crear talles</h1>

            </section>
        </ContainerBase>
    )
}

export default Sizes;
