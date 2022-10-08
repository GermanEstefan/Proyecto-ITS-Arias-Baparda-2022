
import React, { useEffect, useState } from 'react'
import { fetchApi } from '../../API/api';
import ContainerBase from '../../components/admin/ContainerBase';

const Sizes = () => {

    const [loadingFlags, setLoadingFlags] = useState({ fetchingSizes: true });
    const [sizes, setSizes] = useState([]);
 
    useEffect( () => {
        fetchApi('sizes.php', 'GET')
            .then( sizes => {
                console.log(sizes);
                if(!sizes) return;
                setSizes(sizes);
            })
            .catch( err => console.error(err))
            .finally(() => setLoadingFlags({...loadingFlags, fetchingSizes: false}))
    },[])

    return (
        <ContainerBase>
            <section className='container_section flex-column-center-xy'>
                
                {
                    loadingFlags
                    ? <span>Obteniendo talles...</span>
                    : <>
                    <h1>Talles</h1>
                        
                    </>
                }
            </section>
        </ContainerBase>
    )
}

export default Sizes;
