import React from "react";
import { Link } from "react-router-dom";
import NoPhoto from "../../assets/img/no-photo.png";

const Card = ({ img, title, to }) => {
  return (
    <div className="card">
      <Link to={`/category/${to}`}>
        <img src={img? img : NoPhoto} alt="" />
        <div className="text-container">
          <h2>{title}</h2>
        </div>
      </Link>
    </div>
  );
};

export default Card;
