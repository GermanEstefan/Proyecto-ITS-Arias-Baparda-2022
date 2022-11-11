/** @format */

import React, { useState, useEffect } from "react";
import { useParams } from "react-router-dom";
import NoPhoto from "../../assets/img/no-photo.png";
import ContainerBase from "../../components/store/ContainerBase";
import PageTitle from "../../components/store/PageTitle";
import Pagination from "../../components/store/Pagination";
import { fetchApi } from "../../API/api";
import ProductCard from "../../components/store/ProductCard";
import Swal from "sweetalert2";


const SearchResultsPage = () => {
  const { data } = useParams();
  const [currentPage, setCurrentPage] = useState(1);
  const [itemsPerPage, setItemsPerPage] = useState(3);
  const [productList, setProductList] = useState([]);
  useEffect(() => {
    window.scroll(0, 0);
    getProductsBySearchInput();
  }, []);
  useEffect(() => {
    getProductsBySearchInput();
  }, [data]);
  const getProductsBySearchInput = async () => {
    try {
      const resp = await fetchApi(`products.php?name=${data}`, "GET");
      setProductList(resp.result.data);
    } catch (error) {
      console.error(error);
      return Swal.fire({
        icon: "error",
        text: "Error 500, servidor caido",
        timer: 3000,
        showConfirmButton: true,
      });
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
        <PageTitle title={`Resultados de ${data}`} isArrow={true} arrowGoTo={"/"} />

        <div className="card-container">
          {productList.length === 0 && <p>No hay productos en esta categoría</p>}
          {currentItems.map((product, index) => {
            return (
              <ProductCard
                className="animate__animated animate__bounce"
                product={product.name}
                description={product.description}
                img={product.picture ? product.picture : NoPhoto}
                key={index}
                id={product.id_product}
                categoryFromProps={product.category}
              />
            );
          })}
        </div>
        {productList.length > itemsPerPage && (
          <Pagination currentPage={currentPage} itemsPerPage={itemsPerPage} totalItems={productList.length} paginate={paginate} />
        )}
      </div>
    </ContainerBase>
  );
};

export default SearchResultsPage;
