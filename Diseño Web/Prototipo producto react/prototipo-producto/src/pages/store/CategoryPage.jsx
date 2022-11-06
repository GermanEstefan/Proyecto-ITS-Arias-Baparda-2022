/** @format */

import React, { useState, useEffect } from "react";
import { useParams } from "react-router-dom";
import NoPhoto from "../../assets/img/no-photo.png";
import ContainerBase from "../../components/store/ContainerBase";
import PageTitle from "../../components/store/PageTitle";
import Pagination from "../../components/store/Pagination";
import { fetchApi } from "../../API/api";
import ProductCard from "../../components/store/ProductCard";
import NoData from "../../components/store/NoData";

const CategoryPage = () => {
  const { category } = useParams();
  const [currentPage, setCurrentPage] = useState(1);
  const [itemsPerPage] = useState(3);
  const [productList, setProductList] = useState([]);
  useEffect(() => {
    window.scroll(0, 0);
    window.scrollTo( 0, 0 );
    getProductsByCategory();
  }, []);

  const getProductsByCategory = async () => {
    if (category === "PROMOCIONES") {
      const resp = await fetchApi(`products.php?promos`, "GET");
      setProductList(resp.result.data);
    } else {
      const resp = await fetchApi(`products.php?categoryName=${category}`, "GET");
      setProductList(resp.result.data);
    }
  };

  const indexOfLastItem = currentPage * itemsPerPage;
  const indexOfFirstItem = indexOfLastItem - itemsPerPage;
  const currentItems = productList.slice(indexOfFirstItem, indexOfLastItem);

  const paginate = (number) => {
    setCurrentPage(currentPage + number);
  };

  return (
    <ContainerBase>
      <div className="main">
        <PageTitle title={category} isArrow={true} arrowGoTo={"/"} />

        <div className="card-container">
          {productList.length === 0 && <NoData message={'No hay productos en esta categoría'}/>}
          {currentItems.map((product, index) => {
            return (
              <ProductCard
                className="animate__animated animate__bounce"
                product={product.name}
                description={product.description}
                img={product.picture ? product.picture : NoPhoto}
                key={index}
                id={product.id_product}
                price={product.price}
              />
            );
          })}
        </div>
        {productList.length > itemsPerPage && (
          <Pagination
            currentPage={currentPage}
            itemsPerPage={itemsPerPage}
            totalItems={productList.length}
            paginate={paginate}
          />
        )}
      </div>
    </ContainerBase>
  );
};

export default CategoryPage;
