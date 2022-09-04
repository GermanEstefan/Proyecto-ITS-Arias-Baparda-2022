import React from "react";
import { useMediaQuery } from "react-responsive";
import Footer from "./Footer";
import Header from "./Header";
import ShoppingCart from "./ShoppingCart";

const ContainerBase = ({ children }) => {

    const isMobile = useMediaQuery({ query: "(max-width: 800px)" });

    return (
        <>
            <Header />
            {children}
            {isMobile && <ShoppingCart />}
            <Footer />
        </>
    )
}

export default ContainerBase;