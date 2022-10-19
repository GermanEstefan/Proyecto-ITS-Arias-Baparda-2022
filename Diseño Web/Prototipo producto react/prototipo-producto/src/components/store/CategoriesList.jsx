import React, { useEffect, useState } from "react";
import { fetchApi } from "../../API/api";
import Guantes from "../../assets/img/guantes.jpg";
import Card from "./Card";

const CategoriesList = () => {
  const [categories, setCategories] = useState([]);

  useEffect(() => {
    getCategories();
  }, []);

  const getCategories = async () => {
    const resp = await fetchApi("categorys.php", "GET");
    setCategories(resp.result.data);
  };

  return (
    <div className="card-container">
      {categories.map((category, index) => {
        return (
          <Card
            key={index}
            title={category.name}
            to={category.name}
            slug={category.name}
            img={Guantes}
          />
        );
      })}

      {categories.length === 0 && <p>No pudimos cargar ninguna categoría</p>}
    </div>
  );
};

export default CategoriesList;
