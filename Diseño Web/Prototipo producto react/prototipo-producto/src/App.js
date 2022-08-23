import React, { Fragment, createContext, useEffect, useState } from "react";
import HomaPage from "./pages/HomaPage";
import { useNavigate } from "react-router-dom";
import { Routes, Route } from "react-router-dom";
import ContactPage from "./pages/ContactPage";
import CategoryPage from "./pages/CategoryPage";
import Navbar from "./components/Navbar";
import Footer from "./components/Footer";
import Login from "./pages/Login";
import Register from "./pages/Register";
import ProductPage from "./pages/ProductPage";
import { verifyAuth } from "./helpers/verifyAuth";

export const userStatusContext = createContext({});

function App() {
  const [userData, setUserData] = useState({});
  const [checkingAuth, setCheckingAuth] = useState(true);
  const navigate = useNavigate();
  console.log("--App--");
  useEffect(() => {
    verifyAuth().then((resp) => {
      console.log(resp);
      if (!resp) {
        setCheckingAuth(false);
        return;
      }
      setCheckingAuth(false);
      console.log('useEffect')
      console.log(resp.result.data)
      navigate("/");
    });
  }, []);
  console.log(userData)

  return (
    <userStatusContext.Provider value={{ userData, setUserData }}>
      {checkingAuth ? (
        <h1>Checking auth...</h1>
      ) : (
        <Fragment>
          <Navbar />
          {
            (!userData.name) ?
              <Login></Login>
            :
            <Routes>
              <Route path="/" element={<HomaPage />} />
              <Route path="/contact" element={<ContactPage />} />
              <Route path="/:category" element={<CategoryPage />}></Route>
              <Route path="/login" element={<Login />} />
              <Route path="/register" element={<Register />} />
              <Route path="/:category/:name" element={<ProductPage />} />
            </Routes>
          }
          <Footer />
        </Fragment>
      )}
    </userStatusContext.Provider>
  );
}

export default App;
