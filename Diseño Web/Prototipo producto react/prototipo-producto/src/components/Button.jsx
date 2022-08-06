import React from "react";

const Button = ({ onClick, text }) => {
  return (
    <>
      <div className="button" onClick={onClick} >{text}</div>
    </>
  );
};

export default Button;
