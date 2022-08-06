import React from "react";
import ReactDOM from "react-dom/client";
import "./style.css";
import App from "./App";
import { BrowserRouter, Routes, Route } from "react-router-dom";
import ContactPage from "./pages/ContactPage";
import CategoryPage from "./pages/CategoryPage";



const root = ReactDOM.createRoot(document.getElementById("root"));
root.render(
  <BrowserRouter>
    <Routes>
      <Route path="/" element={<App />}/>
      <Route path="/contact" element={<ContactPage />}/>
      <Route path="/category" element={<CategoryPage />}/>
    </Routes>
  </BrowserRouter>
);
