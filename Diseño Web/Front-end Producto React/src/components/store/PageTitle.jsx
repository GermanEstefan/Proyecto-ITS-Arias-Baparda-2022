import React from "react";
import { useNavigate } from "react-router-dom";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faArrowLeft } from "@fortawesome/free-solid-svg-icons";

const PageTitle = ({
  title,
  isArrow = false,
  arrowGoTo = "/",
  goBack = false,
}) => {
  const navigate = useNavigate();
  return (
    <div className="main_header">
      {isArrow && (
        <div onClick={() => navigate(goBack ? -1 : arrowGoTo)}>
          <FontAwesomeIcon size="2x" icon={faArrowLeft} />
        </div>
      )}
      <h1>{title}</h1>
    </div>
  );
};

export default PageTitle;
