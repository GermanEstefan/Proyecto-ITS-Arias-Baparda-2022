import React, {useEffect} from "react";
import Banner from "../components/Banner";
import CategoriesList from "../components/CategoriesList";

const HomaPage = () => {
useEffect(() => {
  window.scroll(0, 0)
}, [])

  return (
    <>
      <Banner />
      <CategoriesList />
    </>
  );
};

export default HomaPage;
