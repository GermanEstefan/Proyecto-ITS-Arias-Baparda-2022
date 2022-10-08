import { faShoppingCart } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import React from "react";
import { useMediaQuery } from "react-responsive";
import { useNavigate } from "react-router-dom";

const ShoppingCart = () => {

    const navigate  = useNavigate();

    const isMobile = useMediaQuery({ query: "(max-width: 800px)" });

    return(
        <div className={isMobile ? 'shopping-cart-mobile' : 'shopping-cart-desktop'} >
            <span>0</span> {/*Este numero va a ser dinamico*/}
            <FontAwesomeIcon icon={faShoppingCart} onClick={() => navigate('/shoppingCart')} />
        </div>
    )
}

export default ShoppingCart;