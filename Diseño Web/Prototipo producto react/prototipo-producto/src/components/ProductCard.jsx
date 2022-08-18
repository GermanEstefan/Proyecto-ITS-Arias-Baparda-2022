import React from "react";
import { Link, useParams } from "react-router-dom";
import { useMediaQuery } from "react-responsive";
import Card from "./../components/Card";
const ProductCard = ({ img, product, description }) => {
  const { category } = useParams();

  const isMobile = useMediaQuery({ query: "(max-width: 700px)" });

  return isMobile ? (
    <Card img={img} category={product} />
  ) : (
    <div class="product-card">
      <img src={img} width="200px" alt="" />

      <div class="product-text-container">
        <h2>{product}</h2>
        <p>{description}</p>
        <Link to={`/${category}/${product}`}>
          <span>Ver mas</span>
          <i class="fas fa-arrow-right"></i>
        </Link>
      </div>
    </div>
  );
};

export default ProductCard;
  