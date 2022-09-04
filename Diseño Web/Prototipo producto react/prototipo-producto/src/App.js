import React, { createContext } from "react";
import { Routes, Route } from "react-router-dom";
import Footer from "./components/store/Footer";
import Register from "./pages/store/Register";
import Header from "./components/store/Header";
import CategoryPage from "./pages/store/CategoryPage";
import Login from "./pages/store/Login";
import ProductPage from "./pages/store/ProductPage";
import Contact from "./pages/store/Contact";
import Home from "./pages/store/Home";
import ShoppingCart from "./components/store/ShoppingCart";
import { useMediaQuery } from "react-responsive";
import Loading from "./components/store/Loading";
import useAuth from "./hooks/useAuth";

export const userStatusContext = createContext({});

const App = () => {

  const { userData, setUserData, isChecking } = useAuth({
    name: null,
    surname: null,
    auth: false
  });

  const isMobile = useMediaQuery({ query: "(max-width: 800px)" });

  return (
    isChecking
    ?
    <Loading />
    :
    <>
      <userStatusContext.Provider value={{ userData, setUserData }}>
        <Header />
        <Routes>
          <Route path="/" element={<Home />} />
          <Route path="/login" element={<Login />} />
          <Route path="/register" element={<Register />} />
          <Route path="/contact" element={<Contact />} />
          <Route path="/category/:category" element={<CategoryPage />} />
          <Route path="/category/:category/:id" element={<ProductPage />} />
        </Routes>
        {isMobile && <ShoppingCart />}
        <Footer />
      </userStatusContext.Provider>
    </>
  );
}

export default App;
