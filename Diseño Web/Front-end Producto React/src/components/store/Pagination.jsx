import React from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faArrowLeft } from "@fortawesome/free-solid-svg-icons";
import { faArrowRight } from "@fortawesome/free-solid-svg-icons";
const Pagination = ({ currentPage, itemsPerPage, totalItems, paginate }) => {
  const pageNumbers = [];

  for (let i = 1; i <= Math.ceil(totalItems / itemsPerPage); i++) {
    pageNumbers.push(i);
  }

  return (
    <nav>
      <div className="paginationContainer">
        <span className="pagination">
          <button
            disabled={currentPage === 1}
            className="paginationBtn"
            onClick={() => paginate(-1)}
          >
            <FontAwesomeIcon className="icon" icon={faArrowLeft} />
          </button>
        </span>
        <span className="pagination">
          <button
            disabled={currentPage === pageNumbers[pageNumbers.length - 1]}
            className="paginationBtn"
            onClick={() => paginate(1)}
          >
            <FontAwesomeIcon icon={faArrowRight} />
          </button>
        </span>
      </div>
    </nav>
  );
};

export default Pagination;
