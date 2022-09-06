import React, { useState } from "react";
import { useParams } from "react-router-dom";
import Guantes from "../../assets/img/guantes.jpg";
import PageTitle from "../../components/store/PageTitle";
import Pagination from "../../components/store/Pagination";
import ProductCard from "../../components/store/ProductCard";

const CategoryPage = () => {
  const { category } = useParams();
  const [currentPage, setCurrentPage] = useState(1);
  const [itemsPerPage, setItemsPerPage] = useState(3);

  /*
    Esta pantalla va a tener un endpoint que va a traer
    todos los item en base al nombre de la categoria.
    El valor "category" obtenido por parametros solo es para hacer
    la request. 
  */

  const productsList = [
    {
      id:1,
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
              id={product.id}
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
