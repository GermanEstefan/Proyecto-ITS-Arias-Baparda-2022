import React, { createContext, useEffect, useState } from "react";
import HomaPage from "./pages/HomaPage";
import { Routes, Route } from "react-router-dom";
import ContactPage from "./pages/ContactPage";
import CategoryPage from "./pages/CategoryPage";
import Navbar from "./components/Navbar";
import Footer from "./components/Footer";
import Login from "./pages/Login";
import Register from "./pages/Register";
import ProductPage from "./pages/ProductPage";
import { verifyAuth } from "./helpers/verifyAuth";
import { useNavigate } from "react-router-dom";

export const userStatusContext = createContext({});

function App() {
  const [userData, setUserData] = useState({});
  const [isChecking, setIsChecking] = useState(false);
  const navigate = useNavigate();

  useEffect(() => {
    setIsChecking(true);
    window.scroll(0, 0);
    verifyAuth()
      .then( res => {
        setIsChecking(true);
        if (!res) {
          setIsChecking(false);
          return;
        }
        setUserData(res.result.data);
        navigate('/')
        setIsChecking(false);
      })
  },[])



return (
  <userStatusContext.Provider value={{ userData, setUserData }}>
    <>
      {isChecking ? (
        <h1>Checking...</h1>
      ) :
        <>
          <Navbar />
          <Routes>
            <Route path="/" element={<HomaPage />} />
            <Route path="/contact" element={<ContactPage />} />
            <Route path="/:category" element={<CategoryPage />}></Route>
            <Route path="/login" element={<Login />} />
            <Route path="/register" element={<Register />} />
            <Route path="/:category/:name" element={<ProductPage />} />
          </Routes>
          <Footer />
        </>
      }
    </>
  </userStatusContext.Provider>
);
}

export default App;
