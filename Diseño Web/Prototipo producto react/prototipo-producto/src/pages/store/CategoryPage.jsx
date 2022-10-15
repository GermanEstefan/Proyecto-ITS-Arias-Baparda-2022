import React, { useState, useEffect } from "react";
import { useParams } from "react-router-dom";
import Guantes from "../../assets/img/guantes.jpg";
import ContainerBase from "../../components/store/ContainerBase";
import PageTitle from "../../components/store/PageTitle";
import Pagination from "../../components/store/Pagination";
import { fetchApi } from "../../API/api";
import ProductCard from "../../components/store/ProductCard";

const CategoryPage = () => {
  const { category } = useParams();
  const [currentPage, setCurrentPage] = useState(1);
  const [itemsPerPage, setItemsPerPage] = useState(1);
  const [productList, setProductList] = useState([]);

  useEffect(() => {
    window.scroll(0, 0);
    getProductsByCategory();
  }, []);

  const getProductsByCategory = async () => {
    const resp = await fetchApi(`products.php?categoryName=${category}`, "GET");
    console.log(resp);
    setProductList(resp.result.data);
  };

  //   {
  //     id: 1,
  //     name: "tuki",
  //     description:
  //       "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quidem consequuntur animi aliquid nulla rem nostrum nesciunt voluptas ea quos quo cum, ratione non voluptatibus! Iure recusandae officiis nostrum quasi dolor.",
  //   },
  //   {
  //     name: "flama",
  //     description:
  //       "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quidem consequuntur animi aliquid nulla rem nostrum nesciunt voluptas ea quos quo cum, ratione non voluptatibus! Iure recusandae officiis nostrum quasi dolor.",
  //   },
  //   {
  //     name: "joya",
  //     description:
  //       "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quidem consequuntur animi aliquid nulla rem nostrum nesciunt voluptas ea quos quo cum, ratione non voluptatibus! Iure recusandae officiis nostrum quasi dolor.",
  //   },
  //   {
  //     name: "fiera",
  //     description:
  //       "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quidem consequuntur animi aliquid nulla rem nostrum nesciunt voluptas ea quos quo cum, ratione non voluptatibus! Iure recusandae officiis nostrum quasi dolor.",
  //   },
  //   {
  //     name: "godines",
  //     description:
  //       "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quidem consequuntur animi aliquid nulla rem nostrum nesciunt voluptas ea quos quo cum, ratione non voluptatibus! Iure recusandae officiis nostrum quasi dolor.",
  //   },
  //   {
  //     name: "fructifero",
  //     description:
  //       "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quidem consequuntur animi aliquid nulla rem nostrum nesciunt voluptas ea quos quo cum, ratione non voluptatibus! Iure recusandae officiis nostrum quasi dolor.",
  //   },
  //   {
  //     name: "ese lente > 🕶️",
  //     description:
  //       "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quidem consequuntur animi aliquid nulla rem nostrum nesciunt voluptas ea quos quo cum, ratione non voluptatibus! Iure recusandae officiis nostrum quasi dolor.",
  //   },
  //   {
  //     name: "tuki",
  //     description:
  //       "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quidem consequuntur animi aliquid nulla rem nostrum nesciunt voluptas ea quos quo cum, ratione non voluptatibus! Iure recusandae officiis nostrum quasi dolor.",
  //   },
  //   {
  //     name: "flama",
  //     description:
  //       "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quidem consequuntur animi aliquid nulla rem nostrum nesciunt voluptas ea quos quo cum, ratione non voluptatibus! Iure recusandae officiis nostrum quasi dolor.",
  //   },
  //   {
  //     name: "joya",
  //     description:
  //       "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quidem consequuntur animi aliquid nulla rem nostrum nesciunt voluptas ea quos quo cum, ratione non voluptatibus! Iure recusandae officiis nostrum quasi dolor.",
  //   },
  //   {
  //     name: "fiera",
  //     description:
  //       "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quidem consequuntur animi aliquid nulla rem nostrum nesciunt voluptas ea quos quo cum, ratione non voluptatibus! Iure recusandae officiis nostrum quasi dolor.",
  //   },
  //   {
  //     name: "godines",
  //     description:
  //       "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quidem consequuntur animi aliquid nulla rem nostrum nesciunt voluptas ea quos quo cum, ratione non voluptatibus! Iure recusandae officiis nostrum quasi dolor.",
  //   },
  //   {
  //     name: "fructifero",
  //     description:
  //       "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quidem consequuntur animi aliquid nulla rem nostrum nesciunt voluptas ea quos quo cum, ratione non voluptatibus! Iure recusandae officiis nostrum quasi dolor.",
  //   },
  //   {
  //     name: "ese lente > 🕶️",
  //     description:
  //       "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quidem consequuntur animi aliquid nulla rem nostrum nesciunt voluptas ea quos quo cum, ratione non voluptatibus! Iure recusandae officiis nostrum quasi dolor.",
  //   },
  //   {
  //     name: "tuki",
  //     description:
  //       "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quidem consequuntur animi aliquid nulla rem nostrum nesciunt voluptas ea quos quo cum, ratione non voluptatibus! Iure recusandae officiis nostrum quasi dolor.",
  //   },
  //   {
  //     name: "flama",
  //     description:
  //       "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quidem consequuntur animi aliquid nulla rem nostrum nesciunt voluptas ea quos quo cum, ratione non voluptatibus! Iure recusandae officiis nostrum quasi dolor.",
  //   },
  //   {
  //     name: "joya",
  //     description:
  //       "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quidem consequuntur animi aliquid nulla rem nostrum nesciunt voluptas ea quos quo cum, ratione non voluptatibus! Iure recusandae officiis nostrum quasi dolor.",
  //   },
  //   {
  //     name: "fiera",
  //     description:
  //       "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quidem consequuntur animi aliquid nulla rem nostrum nesciunt voluptas ea quos quo cum, ratione non voluptatibus! Iure recusandae officiis nostrum quasi dolor.",
  //   },
  //   {
  //     name: "godines",
  //     description:
  //       "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quidem consequuntur animi aliquid nulla rem nostrum nesciunt voluptas ea quos quo cum, ratione non voluptatibus! Iure recusandae officiis nostrum quasi dolor.",
  //   },
  //   {
  //     name: "fructifero",
  //     description:
  //       "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quidem consequuntur animi aliquid nulla rem nostrum nesciunt voluptas ea quos quo cum, ratione non voluptatibus! Iure recusandae officiis nostrum quasi dolor.",
  //   },
  //   {
  //     name: "ese lente > 🕶️",
  //     description:
  //       "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quidem consequuntur animi aliquid nulla rem nostrum nesciunt voluptas ea quos quo cum, ratione non voluptatibus! Iure recusandae officiis nostrum quasi dolor.",
  //   },
  // ];

  const indexOfLastItem = currentPage * itemsPerPage;
  const indexOfFirstItem = indexOfLastItem - itemsPerPage;
  const currentItems = productList.slice(indexOfFirstItem, indexOfLastItem);

  const paginate = (number) => {
    setCurrentPage(currentPage + number);
  };

  return (
    <ContainerBase>
      <div className="main">
        <PageTitle title={category} isArrow={true} />

        <div className="card-container">
          {
            productList.length === 0 && (
              <p>No hay productos en esta categoría</p>
            )
          }
          {currentItems.map((product, index) => {
            return (
              <ProductCard
                className="animate__animated animate__bounce"
                product={product.name}
                description={product.description}
                img={Guantes}
                key={index}
                id={product.id_product}
              />
            );
          })}
        </div>
        {productList.length !== 0 && 
          <Pagination
            currentPage={currentPage}
            itemsPerPage={itemsPerPage}
            totalItems={productList.length}
            paginate={paginate}
          />}
      </div>
    </ContainerBase>
  );
};

export default CategoryPage;
