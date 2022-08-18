import React from "react";
import { Link } from "react-router-dom";

const Card = ({img, category}) => {
  
  return (
    <div className="card">
      <Link to={`/${category}`}>
        <img src={img} alt="" />

        <div className="text-container">
          <h2>{category}</h2>
        </div>
      </Link>
    </div>
  );
};

export default Card;
