import React from "react";
import { Link } from "react-router-dom";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faArrowLeft } from '@fortawesome/free-solid-svg-icons'

const PageTitle = ({ title, isArrow, arrowGoTo = '/'}) => {
  return (
    <div className="main_header">
      {
          isArrow && (<Link to={arrowGoTo}>
          <FontAwesomeIcon size="2x" icon={faArrowLeft} />
        </Link>)
      }
      <h1>{title}</h1>
    </div>
  );
};

export default PageTitle;
