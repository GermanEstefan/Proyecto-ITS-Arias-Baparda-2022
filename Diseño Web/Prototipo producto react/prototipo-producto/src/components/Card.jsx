import React from "react";

const Card = ({goTo, img, title}) => {
  return (
    <div className="card">
      <a href={goTo}>
        <img src={img} alt="" />

        <div className="text-container">
          <h2>{title}</h2>
        </div>
      </a>
    </div>
  );
};

export default Card;
