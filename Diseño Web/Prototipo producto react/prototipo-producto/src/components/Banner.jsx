import React from "react";
import BannerImage from './../img/Banner.jpg'

const Banner = () => {
  return (
    <>
      <div className="banner_container">
        <img src={BannerImage} alt="" />
        <div>
          Nos encargamos de distribuir lo mejor en productos para la seguridad
          laboral en tu empresa ponele
        </div>
      </div>
    </>
  );
};

export default Banner;
