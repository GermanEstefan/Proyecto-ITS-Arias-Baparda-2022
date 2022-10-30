import React from "react";
import { useMediaQuery } from "react-responsive";
import Footer from "./Footer";
import Header from "./Header";
import ShoppingCart from "./ShoppingCart";

const ContainerBase = ({ children }) => {

    const isMobile = useMediaQuery({ query: "(max-width: 800px)" });

    return (
        <main className="main-client">
            <Header />
            {children}
            {isMobile && <ShoppingCart />}
            <Footer />
        </main>
    )
}

export default ContainerBase;