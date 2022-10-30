/** @format */

import React from "react";
import { Link, useParams } from "react-router-dom";
import { useMediaQuery } from "react-responsive";
import Card from "./Card";
import { Animated } from "react-animated-css";
import NoPhoto from "../../assets/img/no-photo.png";

const ProductCard = ({ img, product, description, id, categoryFromProps }) => {
  const { category } = useParams();
  const isMobile = useMediaQuery({ query: "(max-width: 700px)" });

  return (
    <Animated
      animationIn="fadeInLeft"
      animationOut="fadeOut"
      animationInDuration={500}
      isVisible={true}
    >
      {isMobile ? (
        <Card img={img} title={product} to={`${product}/${id}`} />
      ) : (
        <div className="product-card">
          <img src={img ? img : NoPhoto} width="200px" alt="" />

          <div className="product-text-container">
            <h2>{product}</h2>
            <p>{description}</p>
            <Link to={`/category/${category || categoryFromProps}/${id}`}>
              <span>Ver mas</span>
              <i className="fas fa-arrow-right"></i>
            </Link>
          </div>
        </div>
      )}
    </Animated>
  );
};

export default ProductCard;
