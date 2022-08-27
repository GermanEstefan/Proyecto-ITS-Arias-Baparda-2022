import React, { useState, useEffect } from "react";
import { useParams } from "react-router-dom";
import PageTitle from "../components/PageTitle";
import ProductCard from "../components/ProductCard";
import Guantes from "./../img/guantes.jpg";
import { Animated } from "react-animated-css";
import Pagination from "../components/Pagination";

const CategoryPage = () => {
  const { category } = useParams();

  const [currentPage, setCurrentPage] = useState(1);
  const [itemsPerPage, setItemsPerPage] = useState(3);

  const productsList = [
    {
      name: "tuki",
      description:
        "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quidem consequuntur animi aliquid nulla rem nostrum nesciunt voluptas ea quos quo cum, ratione non voluptatibus! Iure recusandae officiis nostrum quasi dolor.",
    },
    {
      name: "flama",
      description:
        "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quidem consequuntur animi aliquid nulla rem nostrum nesciunt voluptas ea quos quo cum, ratione non voluptatibus! Iure recusandae officiis nostrum quasi dolor.",
    },
    {
      name: "joya",
      description:
        "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quidem consequuntur animi aliquid nulla rem nostrum nesciunt voluptas ea quos quo cum, ratione non voluptatibus! Iure recusandae officiis nostrum quasi dolor.",
    },
    {
      name: "fiera",
      description:
        "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quidem consequuntur animi aliquid nulla rem nostrum nesciunt voluptas ea quos quo cum, ratione non voluptatibus! Iure recusandae officiis nostrum quasi dolor.",
    },
    {
      name: "godines",
      description:
        "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quidem consequuntur animi aliquid nulla rem nostrum nesciunt voluptas ea quos quo cum, ratione non voluptatibus! Iure recusandae officiis nostrum quasi dolor.",
    },
    {
      name: "fructifero",
      description:
        "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quidem consequuntur animi aliquid nulla rem nostrum nesciunt voluptas ea quos quo cum, ratione non voluptatibus! Iure recusandae officiis nostrum quasi dolor.",
    },
    {
      name: "ese lente > 🕶️",
      description:
        "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quidem consequuntur animi aliquid nulla rem nostrum nesciunt voluptas ea quos quo cum, ratione non voluptatibus! Iure recusandae officiis nostrum quasi dolor.",
    },
    {
      name: "tuki",
      description:
        "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quidem consequuntur animi aliquid nulla rem nostrum nesciunt voluptas ea quos quo cum, ratione non voluptatibus! Iure recusandae officiis nostrum quasi dolor.",
    },
    {
      name: "flama",
      description:
        "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quidem consequuntur animi aliquid nulla rem nostrum nesciunt voluptas ea quos quo cum, ratione non voluptatibus! Iure recusandae officiis nostrum quasi dolor.",
    },
    {
      name: "joya",
      description:
        "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quidem consequuntur animi aliquid nulla rem nostrum nesciunt voluptas ea quos quo cum, ratione non voluptatibus! Iure recusandae officiis nostrum quasi dolor.",
    },
    {
      name: "fiera",
      description:
        "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quidem consequuntur animi aliquid nulla rem nostrum nesciunt voluptas ea quos quo cum, ratione non voluptatibus! Iure recusandae officiis nostrum quasi dolor.",
    },
    {
      name: "godines",
      description:
        "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quidem consequuntur animi aliquid nulla rem nostrum nesciunt voluptas ea quos quo cum, ratione non voluptatibus! Iure recusandae officiis nostrum quasi dolor.",
    },
    {
      name: "fructifero",
      description:
        "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quidem consequuntur animi aliquid nulla rem nostrum nesciunt voluptas ea quos quo cum, ratione non voluptatibus! Iure recusandae officiis nostrum quasi dolor.",
    },
    {
      name: "ese lente > 🕶️",
      description:
        "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quidem consequuntur animi aliquid nulla rem nostrum nesciunt voluptas ea quos quo cum, ratione non voluptatibus! Iure recusandae officiis nostrum quasi dolor.",
    },
    {
      name: "tuki",
      description:
        "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quidem consequuntur animi aliquid nulla rem nostrum nesciunt voluptas ea quos quo cum, ratione non voluptatibus! Iure recusandae officiis nostrum quasi dolor.",
    },
    {
      name: "flama",
      description:
        "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quidem consequuntur animi aliquid nulla rem nostrum nesciunt voluptas ea quos quo cum, ratione non voluptatibus! Iure recusandae officiis nostrum quasi dolor.",
    },
    {
      name: "joya",
      description:
        "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quidem consequuntur animi aliquid nulla rem nostrum nesciunt voluptas ea quos quo cum, ratione non voluptatibus! Iure recusandae officiis nostrum quasi dolor.",
    },
    {
      name: "fiera",
      description:
        "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quidem consequuntur animi aliquid nulla rem nostrum nesciunt voluptas ea quos quo cum, ratione non voluptatibus! Iure recusandae officiis nostrum quasi dolor.",
    },
    {
      name: "godines",
      description:
        "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quidem consequuntur animi aliquid nulla rem nostrum nesciunt voluptas ea quos quo cum, ratione non voluptatibus! Iure recusandae officiis nostrum quasi dolor.",
    },
    {
      name: "fructifero",
      description:
        "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quidem consequuntur animi aliquid nulla rem nostrum nesciunt voluptas ea quos quo cum, ratione non voluptatibus! Iure recusandae officiis nostrum quasi dolor.",
    },
    {
      name: "ese lente > 🕶️",
      description:
        "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quidem consequuntur animi aliquid nulla rem nostrum nesciunt voluptas ea quos quo cum, ratione non voluptatibus! Iure recusandae officiis nostrum quasi dolor.",
    },
  ];

  const indexOfLastItem = currentPage * itemsPerPage;
  const indexOfFirstItem = indexOfLastItem - itemsPerPage;
  const currentItems = productsList.slice(indexOfFirstItem, indexOfLastItem);

  const paginate = (number) => {
    setCurrentPage(currentPage + number);
  };

  useEffect(() => {
    window.scroll(0, 0);
  }, []);

  return (
    <div className="main">
      <PageTitle title={category} isArrow={true} />

      <div className="card-container">
        {currentItems.map((product, index) => {
          return (
            <ProductCard
              className="animate__animated animate__bounce"
              product={product.name}
              description={product.description}
              img={Guantes}
              key={index}
            />
          );
        })}
        <Pagination
        currentPage={currentPage}
          itemsPerPage={itemsPerPage}
          totalItems={productsList.length}
          paginate={paginate}
        />
      </div>
    </div>
  );
};

export default CategoryPage;
