import React from "react";
import Guantes from "../../assets/img/guantes.jpg";
import Card from "./Card";

const CategoriesList = () => {

  const categoriesList = [
    {
      name: "Accesorios",
      slug:"accessories"
    },
    {
      name: "Herramientas",
      slug: "tools"
    },
    {
      name: "Higiene",
      slug: "Higiene en ingles"
    },
    {
      name: "Zapatos",
      slug: "shoes"
    },
    {
      name: "Otras",
      slug: "others"
    },
  ];

  return (
    <div className="card-container">
      {
        categoriesList.map((category, index) => {
          return (
            <Card key={index} title={category.name} slug={category.slug} img={Guantes} />
          );
        })
      }
    </div>
  );
};

export default CategoriesList;
