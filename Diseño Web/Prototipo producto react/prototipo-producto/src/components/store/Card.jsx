import React from "react";
import { Link, useParams } from "react-router-dom";

const Card = ({ img, title, to }) => {
  const { category } = useParams();
  return (
    <div className="card">
      <Link to={`/category/${to}`}>
        <img src={img} alt="" />
        <div className="text-container">
          <h2>{title}</h2>
        </div>
      </Link>
    </div>
  );
};

export default Card;
