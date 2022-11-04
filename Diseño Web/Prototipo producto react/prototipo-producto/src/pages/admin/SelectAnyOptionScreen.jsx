
import { faStar } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import React, { useContext } from "react";
import { userStatusContext } from "../../App";
import { capitalizeString } from "../../helpers/capitalizeString";

const SelectAnyOptionScreen = () => {

    const { userData } = useContext(userStatusContext);
    const { name, surname} = userData;

    return(
        <section className="container_section select-any-opt flex-column-center-xy">
            <div className="flex-column-center-xy">
                <h1>Bienvenido :</h1> 
                <h2>{ (name && surname) && capitalizeString(`${name} ${surname}`)}</h2>
                <p>Seleccione alguna opcion del menu para comenzar la gestion</p>
                <FontAwesomeIcon icon={faStar} />
            </div>
        </section>
    )
}

export default SelectAnyOptionScreen;