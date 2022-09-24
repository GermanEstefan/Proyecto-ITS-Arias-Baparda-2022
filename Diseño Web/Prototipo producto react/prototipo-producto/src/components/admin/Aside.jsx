import { faChevronDown, faShippingFast, faSprayCanSparkles, faStar, faUser } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import LogoCliente from "../../assets/img/Cliente-logo1.svg";
import React from "react";
import { useState } from "react";

const Aside = () => {

    const visibilityInit = { users: false, categorys: false, products: false, shipments: false }
    const [visibility, setVisibility] = useState(visibilityInit);

    return (
        <aside>
            <img src={LogoCliente} alt="logo" />
            <ul className="aside_menu">
                <li className="aside_menu_item">
                    <div className="aside_menu_item_container" onClick={() => setVisibility({ ...visibilityInit, users: !visibility.users })}>
                        <FontAwesomeIcon icon={faUser} className="aside_menu_item_container_icon"/>
                        <span>Usuarios</span>
                        <FontAwesomeIcon icon={faChevronDown}  className="aside_menu_item_container_icon2"/>
                    </div>
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

                <li className="aside_menu_item">
                    <div className="aside_menu_item_container" onClick={() => setVisibility({ ...visibilityInit, categorys: !visibility.categorys })}>
                        <FontAwesomeIcon icon={faSprayCanSparkles} className="aside_menu_item_container_icon"/>
                        <span>Categorias</span>
                        <FontAwesomeIcon icon={faChevronDown} className="aside_menu_item_container_icon2" />
                    </div>
                    {
                        visibility.categorys
                        &&
                        <ul>
                            <li>Crear una nueva categoria</li>
                            <li>Listar categorias</li>
                        </ul>
                    }
                </li>

                <li className="aside_menu_item">
                    <div className="aside_menu_item_container" onClick={() => setVisibility({ ...visibilityInit, products: !visibility.products })} >
                        <FontAwesomeIcon icon={faStar} className="aside_menu_item_container_icon"/>
                        <span >Productos</span>
                        <FontAwesomeIcon icon={faChevronDown} className="aside_menu_item_container_icon2"/>
                    </div>
                    {
                        visibility.products
                        &&
                        <ul>
                            <li>Añadir un nuevo producto</li>
                            <li>Listar productos</li>
                        </ul>
                    }
                </li>

                <li className="aside_menu_item">
                    <div className="aside_menu_item_container" onClick={() => setVisibility({ ...visibilityInit, shipments: !visibility.shipments })}>
                        <FontAwesomeIcon icon={faShippingFast} className="aside_menu_item_container_icon"/>
                        <span>Envios</span>
                        <FontAwesomeIcon icon={faChevronDown} className="aside_menu_item_container_icon2"/>
                    </div>
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
    )
}

export default Aside;