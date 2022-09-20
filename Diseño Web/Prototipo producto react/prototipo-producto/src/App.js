import React, { createContext } from "react";
import { Routes, Route } from "react-router-dom";
import Register from "./pages/store/Register";
import CategoryPage from "./pages/store/CategoryPage";
import Login from "./pages/store/Login";
import ProductPage from "./pages/store/ProductPage";
import Contact from "./pages/store/Contact";
import Home from "./pages/store/Home";
import Loading from "./components/store/Loading";
import useAuth from "./hooks/useAuth";
import UserPanel from "./pages/store/UserPanel";
import LoginAdm from "./pages/admin/LoginAdm";
import Users from "./pages/admin/Users";
import HomeAdmin from "./pages/admin/HomeAdmin";

export const userStatusContext = createContext({});

const App = () => {

  const { userData, setUserData, isChecking } = useAuth({
    name: null,
    surname: null,
    email: null,
    address: null,
    phone: null,
    auth: false
  });

  return (
    isChecking
      ?
      <Loading />
      :
      <userStatusContext.Provider value={{ userData, setUserData }}>
        <Routes>
          <Route path="/" element={ <Home />} />
          <Route path="/login" element={<Login />} />
          <Route path="/register" element={<Register />} />
          <Route path="/contact" element={<Contact />} />
          <Route path="/category/:category" element={<CategoryPage />} />
          <Route path="/category/:category/:id" element={<ProductPage />} />
          <Route path="/panel-user" element={<UserPanel/>} />
          <Route path="/admin/login" element={<LoginAdm/>} />
          <Route path="/admin" element={ <HomeAdmin/> } />
        </Routes>
      </userStatusContext.Provider>
  );
}

export default App;
