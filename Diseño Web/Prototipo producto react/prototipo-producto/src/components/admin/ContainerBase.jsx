import React, { useState } from "react";
import Header from "./HeaderAdm";

const ContainerBase = ({ children }) => {

    const visibilityInit = { users: false, categorys: false, products: false, shipments: false }
    const [visibility, setVisibility] = useState(visibilityInit);

    return (
        <>
            <Header />
            <main className="container-admin">
                <aside className="dashboard_menu">
                    <ul className="dashboard_menu_list">
                        <li className="dashboard_menu_list_item">
                            <span onClick={() => setVisibility({ ...visibilityInit, users: !visibility.users })}>Usuarios</span>
                            {
                                visibility.users
                                &&
                                <ul>
                                    <li>Crear un nuevo usuario</li>
                                    <li>Listar usuarios</li>
                                    <li>Ver acciones realizadas</li>
                                </ul>
                            }

                        </li>

                        <li className="dashboard_menu_list_item">
                            <span onClick={() => setVisibility({ ...visibilityInit, categorys: !visibility.categorys })} >Categorias</span>
                            {
                                visibility.categorys
                                &&
                                <ul>
                                    <li>Crear una nueva categoria</li>
                                    <li>Listar categorias</li>
                                </ul>
                            }
                        </li>

                        <li className="dashboard_menu_list_item">
                            <span onClick={() => setVisibility({ ...visibilityInit, products: !visibility.products })} >Productos</span>
                            {
                                visibility.products
                                &&
                                <ul>
                                    <li>Añadir un nuevo producto</li>
                                    <li>Listar productos</li>
                                </ul>
                            }
                        </li>

                        <li className="dashboard_menu_list_item">
                            <span onClick={() => setVisibility({ ...visibilityInit, shipments: !visibility.shipments })} >Envios</span>
                            {
                                visibility.shipments
                                &&
                                <ul>
                                    <li>Listar envios</li>
                                </ul>
                            }
                        </li>

                    </ul>
                </aside>
                {children}
            </main>
        </>
    )
}

export default ContainerBase;