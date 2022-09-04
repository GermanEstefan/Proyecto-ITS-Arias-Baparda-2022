import React, {useEffect} from "react";
import BannerImage from '../../assets/img/Banner.jpg'
import CategoriesList from "../../components/store/CategoriesList";

const Home = () => {
  useEffect(() => {
    window.scroll(0, 0);
  }, []);

  return (
    <main className="home-page">
      <img src={BannerImage} alt="Snow" className="home-page__banner" />
      <h1 className="home-page__title">CATÁLOGO</h1>
      <CategoriesList />
    </main>
  );
};

export default Home;
