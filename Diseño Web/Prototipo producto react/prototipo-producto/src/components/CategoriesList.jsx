import React from "react";
import Guantes from "./../img/guantes.jpg";
import Card from "./Card";

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
    {
      name: "Otras cosas",
    },
    {
      name: "zapatos",
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
              <Card title={category.name} img={Guantes} />
            );
          })}
        </div>
      </div>
    </>
  );
};

export default CategoriesList;
