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
import ContainerBase from "./components/store/ContainerBase";
import UserPanel from "./pages/store/UserPanel";

export const userStatusContext = createContext({});

const App = () => {

  const { userData, setUserData, isChecking } = useAuth({
    name: null,
    surname: null,
    auth: false
  });

  return (
    isChecking
      ?
      <Loading />
      :
      <userStatusContext.Provider value={{ userData, setUserData }}>
        <Routes>
          <Route path="/" element={<ContainerBase><Home /></ContainerBase>} />
          <Route path="/login" element={<ContainerBase><Login /></ContainerBase>} />
          <Route path="/register" element={<ContainerBase><Register /></ContainerBase>} />
          <Route path="/contact" element={<ContainerBase><Contact /></ContainerBase>} />
          <Route path="/category/:category" element={<ContainerBase><CategoryPage /></ContainerBase>} />
          <Route path="/category/:category/:id" element={<ContainerBase><ProductPage /></ContainerBase>} />
          <Route path="/panel-user" element={<ContainerBase><UserPanel/></ContainerBase>} />
        </Routes>
        <Routes>
          <Route path="/admin/login" element={<h1>Login admin</h1>} />
        </Routes>
      </userStatusContext.Provider>
  );
}

export default App;
