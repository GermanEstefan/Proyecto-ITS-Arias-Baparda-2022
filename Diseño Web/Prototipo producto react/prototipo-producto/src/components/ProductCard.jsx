import React from 'react'
import { Link } from "react-router-dom";

const ProductCard = ({img, title, description}) => {
  return (
    <div class="product-card">
          <img src={img} width="200px" alt="" />

          <div class="product-text-container">
            <h2>{title}</h2>
            <p>
              {description}
            </p>
            <Link to="/">
              <span>Ver mas</span>
              <i class="fas fa-arrow-right"></i>
            </Link>
          </div>
        </div>
  )
}

export default ProductCard