import React, { Fragment } from "react";
import Banner from "./components/Banner";
import CategoriesList from "./components/CategoriesList";
import Footer from "./components/Footer";
import Navbar from "./components/Navbar";
import HomaPage from "./pages/HomaPage";

function App() {
  return (
    <Fragment>
      <Navbar />
      <HomaPage/>
      <Footer />
    </Fragment>
  );
}

export default App;
