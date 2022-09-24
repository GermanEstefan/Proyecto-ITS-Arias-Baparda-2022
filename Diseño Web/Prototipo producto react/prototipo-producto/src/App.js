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
import ContainerBase from "./components/admin/ContainerBase";
import CreateUser from "./pages/admin/CreateUser";
import ListUsers from "./pages/admin/ListUsers";
import ActionUsers from "./pages/admin/ActionUsers";
import CreateCategory from "./pages/admin/CreateCategorys";
import ListCateogorys from "./pages/admin/ListCategorys";
import ListProducts from "./pages/admin/ListProducts";
import CreateProducts from "./pages/admin/CreateProducts";
import ListShipments from "./pages/admin/ListShipments";

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
          <Route path="/admin" element={ <ContainerBase/> } />
          <Route path="/admin/users/create" element={ <CreateUser/> } />
          <Route path="/admin/users/list" element={ <ListUsers/> } />
          <Route path="/admin/users/actions" element={ <ActionUsers/> } />
          <Route path="/admin/categorys/create" element={ <CreateCategory /> } />
          <Route path="/admin/categorys/list" element={ <ListCateogorys/> } />
          <Route path="/admin/products/create" element={ <CreateProducts/> } />
          <Route path="/admin/products/list" element={ <ListProducts/> } />
          <Route path="/admin/shipments/list" element={ <ListShipments/> } />
        </Routes>
      </userStatusContext.Provider>
  );
}

export default App;
