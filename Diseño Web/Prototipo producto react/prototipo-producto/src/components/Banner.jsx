import React, {useContext} from "react";
import BannerImage from './../img/Banner.jpg'
import BannerImage2 from './../img/Escudo.svg'
import { userStatusContext } from "../App";
const Banner = () => {
  const { userData } = useContext(userStatusContext);
  return (
    <>
   
      <div className="banner_container">
        <img src={BannerImage} alt="Snow" />
        <div>
          <img src={BannerImage2} alt="" />
        </div>
      </div>
      {
      userData.name ? <h1>Bienvenido {userData.name}</h1>: <h1>No hay data</h1>
    }
    </>
  );
};

export default Banner;
