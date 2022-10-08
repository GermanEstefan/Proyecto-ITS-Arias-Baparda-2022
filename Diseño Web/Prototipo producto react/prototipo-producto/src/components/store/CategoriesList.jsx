import React, { useEffect, useState } from "react";
import { fetchApi } from "../../API/api";
import Guantes from "../../assets/img/guantes.jpg";
import Card from "./Card";

const CategoriesList = () => {
  const [categories, setcategories] = useState([]);

  useEffect(() => {
    getCategories();  
  }, []);

  const getCategories = async () => {
    setcategories(await fetchApi("categorys.php", "GET"));
  };

  return (
    <div className="card-container">
      {categories.map((category, index) => {
        return (
          <Card
            key={index}
            title={category.name}
            slug={category.name}
            img={Guantes}
          />
        );
      })}
    </div>
  );
};

export default CategoriesList;
