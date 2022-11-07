/** @format */

import React, { createContext, useState } from "react";
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
import UserManagment from "./pages/admin/UserManagment";
import ListProducts from "./pages/admin/ListProducts";
import CreateProducts from "./pages/admin/CreateProducts";
import ListShipments from "./pages/admin/ManageSales";
import ShoppingCartPage from "./pages/store/ShoppingCartPage";
import Categorys from "./pages/admin/Categorys";
import Sizes from "./pages/admin/Sizes";
import Design from "./pages/admin/Design";
import EditCategory from "./pages/admin/EditCategory";
import EditSize from "./pages/admin/EditSize";
import EditDesign from "./pages/admin/EditDesign";
import CreatePromotion from "./pages/admin/CreatePromotion";
import EditModelProduct from "./pages/admin/EditModelProduct";
import EditProducts from "./pages/admin/EditProduct";
import Supplier from "./pages/admin/Supplier";
import EditSupplier from "./pages/admin/EditSupplier";
import BuyProducts from "./pages/admin/BuyProducts";
import ListBuys from "./pages/admin/ListBuys";
import EditPromo from "./pages/admin/EditPromo";
import DetailBuys from "./pages/admin/DetailsBuy";
import UserEdit from "./pages/admin/UserEdit";
import SearchResultsPage from "./pages/store/SearchResultsPage";
import BuyForm from "./pages/store/BuyForm";
import ManageSales from "./pages/admin/ManageSales";
import DetailsSales from "./pages/admin/DetailsSale";
import Queries from "./pages/admin/Queries";

export const userStatusContext = createContext({});
export const cartContext = createContext([]);

const App = () => {
  const { userData, setUserData, isChecking } = useAuth({
    name: null,
    surname: null,
    email: null,
    address: null,
    phone: null,
    auth: false,
  });
  const [cart, setCartState] = useState(() => {
    const saved = localStorage.getItem("cart");
    const initialValue = JSON.parse(saved);
    return initialValue || [];
  });
  const setCart = (newCart) => {
    setCartState(newCart);
    localStorage.setItem("cart", JSON.stringify(newCart));
  };

  return isChecking ? (
    <Loading />
  ) : (
    <userStatusContext.Provider value={{ userData, setUserData }}>
      <cartContext.Provider value={{ cart, setCart }}>
        <Routes>
          <Route path="/" element={<Home />} />
          <Route path="/login" element={!userData.auth ? <Login /> : <Home />} />
          <Route path="/register" element={!userData.auth ? <Register /> : <Home />} />
          <Route path="/contact" element={<Contact />} />
          <Route path="/shoppingCart" element={<ShoppingCartPage />} />
          <Route path="/category/:category" element={<CategoryPage />} />
          <Route path="/category/:category/:id" element={<ProductPage />} />
          <Route path="/results/:data" element={<SearchResultsPage />} />
          <Route path="/panel-user" element={userData.auth ? <UserPanel /> : <Home />} />
          <Route path="/buyForm" element={userData.auth ? <BuyForm /> : <Home />} />
          <Route path="/admin/login" element={<LoginAdm />} />
          <Route path="/admin" element={<ContainerBase />} />
          <Route path="/admin/users/managment" element={<UserManagment />} />
          <Route path="/admin/users/edit/:idUser" element={<UserEdit />} />
          <Route path="/admin/generals/categorys" element={<Categorys />} />
          <Route path="/admin/generals/categorys/edit/:idOfCategory" element={<EditCategory />} />
          <Route path="/admin/generals/sizes" element={<Sizes />} />
          <Route path="/admin/generals/sizes/edit/:idOfSize" element={<EditSize />} />
          <Route path="/admin/generals/designs" element={<Design />} />
          <Route path="/admin/generals/designs/edit/:idOfDesign" element={<EditDesign />} />
          <Route path="/admin/generals/supplier" element={<Supplier />} />
          <Route path="/admin/generals/supplier/edit/:rut" element={<EditSupplier />} />
          <Route path="/admin/products/create" element={<CreateProducts />} />
          <Route path="/admin/products-promo/create" element={<CreatePromotion />} />
          <Route path="/admin/products/edit-model/:barcode" element={<EditModelProduct />} />
          <Route path="/admin/products/edit-product/:idProduct" element={<EditProducts />} />
          <Route path="/admin/products/edit-promo/:idPromo" element={<EditPromo />} />
          <Route path="/admin/products/buy" element={<BuyProducts />} />
          <Route path="/admin/products/buy-list" element={<ListBuys />} />
          <Route path="/admin/products/buy-details/:idBuy" element={<DetailBuys />} />
          <Route path="/admin/shipments/list" element={<ListShipments />} />
          <Route path="/admin/products/list" element={<ListProducts />} />
          <Route path="/admin/sales/manage" element={<ManageSales />} />
          <Route path="/admin/sales/manage/details/:idSale" element={<DetailsSales />} />
          <Route path="/admin/management/queries" element={<Queries />} />
          <Route path="*" element={ <h1> 404 - No existe la ruta especificada</h1> } />
        </Routes>
      </cartContext.Provider>
    </userStatusContext.Provider>
  );
};

export default App;
