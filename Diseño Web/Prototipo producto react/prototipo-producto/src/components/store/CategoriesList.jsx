import React from "react";
import Guantes from "../../assets/img/guantes.jpg";
import Card from "./Card";

const CategoriesList = () => {

  const categoriesList = [
    {
      name: "Accesorios",
    },
    {
      name: "Herramientas",
    },
    {
      name: "Higiene",
    },
    {
      name: "Zapatos",
    },
    {
      name: "Otras",
    },
  ];

  return (
    <div className="card-container">
      {
        categoriesList.map((category, index) => {
          return (
            <Card key={index} category={category.name} img={Guantes} />
          );
        })
      }
    </div>
  );
};

export default CategoriesList;
