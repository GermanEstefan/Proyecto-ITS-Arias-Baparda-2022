import React, { useEffect } from "react";
import { useParams } from "react-router-dom";
import PageTitle from "../components/PageTitle";
import ProductCard from "../components/ProductCard";
import Guantes from "./../img/guantes.jpg";
import { Animated } from "react-animated-css";

const CategoryPage = () => {
  const { category } = useParams();

  useEffect(() => {
    window.scroll(0, 0);
  }, []);


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
  ];

  return (
    <div className="main">
      <PageTitle title={category} isArrow={true} />
      <Animated
        animationIn="fadeInLeft"
        animationOut="fadeOut"
        animationInDuration="500"
        isVisible={true}
      >
        <div className="card-container">
          {productsList.map((product, index) => {
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
        </div>
      </Animated>
    </div>
  );
};

export default CategoryPage;
