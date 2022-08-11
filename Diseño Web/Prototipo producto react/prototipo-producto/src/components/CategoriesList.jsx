import React from "react";
import Guantes from "./../img/guantes.jpg";
import Card from "./Card";
import Navbar from "./Navbar";

const CategoriesList = () => {
  const categoriesList = [
    {
      name: "ACCESORIOS",
    },
    {
      name: "HERRAMIENTAS",
    },
    {
      name: "HIGIENES",
    },
  ];
  return (
    <>
      <div className="main">
        <div className="main_header">
          <h1>CATÁLOGO</h1>
        </div>
        <div className="card-container">
          {categoriesList.map((category) => {
            return (
              <Card title={category.name} img={Guantes} goTo={"./category"} />
            );
          })}
        </div>
      </div>
    </>
  );
};

export default CategoriesList;
