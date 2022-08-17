import React from "react";
import { Link } from "react-router-dom";

const Card = ({img, title}) => {
  
  return (
    <div className="card">
      <Link to={`/${title}`}>
        <img src={img} alt="" />

        <div className="text-container">
          <h2>{title}</h2>
        </div>
      </Link>
    </div>
  );
};

export default Card;
