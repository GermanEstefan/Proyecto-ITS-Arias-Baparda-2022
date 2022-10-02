import { faChevronDown, faShippingFast, faSprayCanSparkles, faStar, faUser } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import LogoCliente from "../../assets/img/Cliente-logo1.svg";
import React,{ useState, useEffect } from "react";
import {  useLocation, useNavigate } from "react-router-dom";

const Aside = () => {

    const visibilityInit = { users: false, generals: false, products: false, shipments: false }
    const [visibility, setVisibility] = useState(visibilityInit);
    const [actualPageAndAction, setActualPageAndAction] = useState({action: '', page: ''});
    const {action, page} = actualPageAndAction;
    const navigate = useNavigate();
    const location = useLocation();

    const handleOpenSubMenu = (submenu) => setVisibility({...visibilityInit, [submenu] : !visibility[submenu] });

    useEffect(() => {
        const actualPathSplitted = location.pathname.split('/').slice(1);
        if(actualPathSplitted.length === 1) return;
        setVisibility({...visibilityInit, [actualPathSplitted[1]]:true })
        setActualPageAndAction({action : actualPathSplitted[2], page: actualPathSplitted[1] })
        //alert(actualPathSplitted)    
    },[])
 
    return (
        <aside>
            <img src={LogoCliente} alt="logo" />
            <ul className="aside_menu">
                <li className={`aside_menu_item`}>
                    <div className="aside_menu_item_container" onClick={() => handleOpenSubMenu('users')} >
                        <FontAwesomeIcon icon={faUser} className="aside_menu_item_container_icon"/>
                        <span>Usuarios</span>
                        <FontAwesomeIcon icon={faChevronDown}  className="aside_menu_item_container_icon2"/>
                    </div>
                    {
                        visibility.users
                        &&
                        <ul>
                            <li 
                                onClick={() => navigate('/admin/users/create') }
                                className={ (action === 'create' && page === 'users') ? 'selected' : '' } 
                            >Crear un nuevo usuario</li>

                            <li 
                                onClick={() => navigate('/admin/users/list') }
                                className={ (action === 'list' && page === 'users') ? 'selected' : '' }
                            >Listar usuarios</li>

                        </ul>
                    }

                </li>

                <li className="aside_menu_item">
                    <div className="aside_menu_item_container" onClick={() => handleOpenSubMenu('generals')} >
                        <FontAwesomeIcon icon={faSprayCanSparkles} className="aside_menu_item_container_icon"/>
                        <span>Generales</span>
                        <FontAwesomeIcon icon={faChevronDown} className="aside_menu_item_container_icon2" />
                    </div>
                    {
                        visibility.generals
                        &&
                        <ul>
                            <li 
                                onClick={() => navigate('/admin/generals/categorys') }
                                className={ (action === 'categorys' && page === 'generals') ? 'selected' : '' }
                            >Categorias</li>

                            <li 
                                onClick={() => navigate('/admin/generals/sizes') }
                                className={ (action === 'sizes' && page === 'generals') ? 'selected' : '' }
                            >Talles</li>

                            <li 
                                onClick={() => navigate('/admin/generals/colors') }
                                className={ (action === 'colors' && page === 'generals') ? 'selected' : '' }
                            >Colores</li>
                        </ul>
                    }
                </li>

                <li className="aside_menu_item">
                    <div className="aside_menu_item_container" onClick={() => handleOpenSubMenu('products')} >
                        <FontAwesomeIcon icon={faStar} className="aside_menu_item_container_icon"/>
                        <span >Productos</span>
                        <FontAwesomeIcon icon={faChevronDown} className="aside_menu_item_container_icon2"/>
                    </div>
                    {
                        visibility.products
                        &&
                        <ul>
                            <li 
                                onClick={() => navigate('/admin/products/create') }
                                className={ (action === 'create' && page === 'products') ? 'selected' : '' }
                            >Añadir un nuevo producto</li>

                            <li 
                                onClick={() => navigate('/admin/products/list') }
                                className={ (action === 'list' && page === 'products') ? 'selected' : '' }
                            >Listar productos</li>
                        </ul>
                    }
                </li>

                <li className="aside_menu_item">
                    <div className="aside_menu_item_container" onClick={() => handleOpenSubMenu('shipments')} >
                        <FontAwesomeIcon icon={faShippingFast} className="aside_menu_item_container_icon"/>
                        <span>Envios</span>
                        <FontAwesomeIcon icon={faChevronDown} className="aside_menu_item_container_icon2"/>
                    </div>
                    {
                        visibility.shipments
                        &&
                        <ul>
                            <li 
                                onClick={() => navigate('/admin/shipments/list') } 
                                className={ (action === 'list' && page === 'shipments') ? 'selected' : '' } 
                            >Listar envios</li>
                        </ul>
                    }
                </li>

            </ul>
        </aside>
    )
}

export default Aside;