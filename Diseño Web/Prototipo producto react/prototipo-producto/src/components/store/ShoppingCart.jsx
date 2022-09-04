import { faShoppingCart } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import React from "react";
import { useMediaQuery } from "react-responsive";

const ShoppingCart = () => {

    const isMobile = useMediaQuery({ query: "(max-width: 800px)" });

    return(
        <div className={isMobile ? 'shopping-cart-mobile' : 'shopping-cart-desktop'} >
            <span>0</span> {/*Este numero va a ser dinamico*/}
            <FontAwesomeIcon icon={faShoppingCart} onClick={() => alert('En proceso de implementacion')} />
        </div>
    )
}

export default ShoppingCart;