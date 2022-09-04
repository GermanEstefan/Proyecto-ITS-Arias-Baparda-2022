import React, { createContext, useEffect, useState } from "react";
import { Routes, Route } from "react-router-dom";
import Footer from "./components/store/Footer";
import Register from "./pages/store/Register";
import { verifyAuth } from "./helpers/verifyAuth";
import { useNavigate } from "react-router-dom";
import Header from "./components/store/Header";
import CategoryPage from "./pages/store/CategoryPage";
import Login from "./pages/store/Login";
import ProductPage from "./pages/store/ProductPage";
import Contact from "./pages/store/Contact";
import Home from "./pages/store/Home";
import ShoppingCart from "./components/store/ShoppingCart";
import { useMediaQuery } from "react-responsive";

export const userStatusContext = createContext({});

function App() {

  const [isChecking, setIsChecking] = useState(false);
  const navigate = useNavigate();

  const [userData, setUserData] = useState({
    name: null,
    surname: null,
    auth: false
  });

  useEffect(() => {
    setIsChecking(true);
    verifyAuth()
      .then(res => {
        setIsChecking(true);
        if (!res) {
          setIsChecking(false);
          return;
        }
        setUserData({
          name: res.result.data.name,
          surname: res.result.data.surname,
          auth: true
        });
        navigate('/')
        setIsChecking(false);
      })
  }, [])

  const isMobile = useMediaQuery({ query: "(max-width: 800px)" });

  return (
    <userStatusContext.Provider value={{ userData, setUserData }}>
      {
        isChecking
          ?
          <h1>Cargando...</h1>
          :
          <>
            <Header />
            <Routes>
              <Route path="/" element={<Home />} />
              <Route path="/login" element={<Login />} />
              <Route path="/register" element={<Register />} />
              <Route path="/contact" element={<Contact />} />
              <Route path="/:category" element={<CategoryPage />} />
              <Route path="/:category/:name" element={<ProductPage />} />
            </Routes>
            { isMobile && <ShoppingCart/>}
            <Footer/>
          </>
      }
    </userStatusContext.Provider>
  );
}

export default App;
