
import React from 'react';
import loadingGif from '../../assets/img/loading.gif';

const Loading = () => {
    return(
        <main className="loading">
            <span>Cargando...</span>
            <img src={loadingGif} alt="Loading"/>
        </main>
    )
}

export default Loading;