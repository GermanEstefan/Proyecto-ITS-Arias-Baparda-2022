/** @format */

import React, { useEffect, useState } from "react";
import { useMediaQuery } from "react-responsive";
import { fetchApi } from "../../API/api";
import NoPhoto from "../../assets/img/no-photo.png";
import Card from "./Card";
import Pagination from "./Pagination";

const CategoriesList = () => {
  const isMobile = useMediaQuery({ query: "(max-width: 800px)" });
  const [currentPage, setCurrentPage] = useState(1);
  const [itemsPerPage, setItemsPerPage] = useState(5);
  const [categories, setCategories] = useState([]);


  useEffect(() => {
    getCategories();
    console.log(isMobile)
    setItemsPerPage(isMobile ? 1 : 5)
    console.log(itemsPerPage)
  }, []);

  const getCategories = async () => {
    const resp = await fetchApi("categorys.php", "GET");

    setCategories(resp.result.data);
  };

  const indexOfLastItem = currentPage * itemsPerPage;
  const indexOfFirstItem = indexOfLastItem - itemsPerPage;
  const currentItems = categories.slice(indexOfFirstItem, indexOfLastItem);

  const paginate = (number) => {
    setCurrentPage(currentPage + number);
  };

  return (
    <div className="card-container">
      {currentItems.map((category, index) => {
        return (
          <Card
            key={index}
            title={category.name}
            to={category.name}
            slug={category.name}
            img={category.picture ? category.picture : NoPhoto}
          />
        );
      })}

      {categories.length === 0 ? (
        <p>No pudimos cargar ninguna categoría</p>
      ) : (
        <Pagination
          currentPage={currentPage}
          itemsPerPage={itemsPerPage}
          totalItems={categories.length}
          paginate={paginate}
        />
      )}
    </div>
  );
};

export default CategoriesList;
