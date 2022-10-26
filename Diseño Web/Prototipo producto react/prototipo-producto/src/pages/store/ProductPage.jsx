import React, { useState, useEffect, useContext } from "react";

import { useParams } from "react-router-dom";
import PageTitle from "../../components/store/PageTitle";
import ContainerBase from "../../components/store/ContainerBase";
import { fetchApi } from "../../API/api";
import { cartContext, userStatusContext } from "../../App";
import Select from "react-select";
import NoPhoto from "../../assets/img/no-photo.png";
const ProductPage = () => {
  const { userData } = useContext(userStatusContext);
  const { category, id } = useParams();
  const { cart, setCart } = useContext(cartContext);
  const [product, setProduct] = useState({});
  const [productName, setproductName] = useState("");
  const [productDescription, setproductDescription] = useState("");
  const [designsList, setDesignsList] = useState([]);
  const [sizesList, setSizesList] = useState([]);
  const [isAddedToCart, setIsAddedToCart] = useState(false);
  const [allModels, setAllModels] = useState([]);
  const [pictureLinks, setPictureLinks] = useState([]);

  useEffect(() => {
    window.scroll(0, 0);
    setPictureLinks(
      "https://i.ibb.co/mRpFg8R/8negro.jpg & https://i.ibb.co/9bKNc4d/remera2.jpg & https://i.ibb.co/9bKNc4d/remera2.jpg".split(
        "&"
      )
    );
    getProductById();
  }, []);

  // useEffect(() => {
  //   setCart(cart.filter(({barcode}) => barcode !== product.barcode));
  // }, [product]);

  const handleAddToCart = () => {
    setCart([
      ...cart,
      { barcode: product.barcode, quantity: 1, price: product.price },
    ]);

    setIsAddedToCart(true);
  };
  const handleDeleteItemFromCart = () => {
    const newCart = cart;
    newCart.pop();
    setCart(newCart);
    setIsAddedToCart(false);
  };

  const getProductById = async () => {
    const resp = await fetchApi(`products.php?modelsOfProduct=${id}`, "GET");
    setProduct(resp.result.data.models[0] ? resp.result.data.models[0] : {});
    setAllModels(resp.result.data.models);
    setproductName(resp.result.data.name);
    setproductDescription(resp.result.data.description);
    getDesignsList(resp.result.data.models);
    getSizesList(resp.result.data.models);
  };

  const getDesignsList = (models) => {
    const designs = [];
    models.forEach((element) => {
      designs.push(element.design);
    });
    setDesignsList([...new Set(designs)]);
  };

  const getSizesList = (models) => {
    const sizes = [];
    models.forEach((element) => {
      sizes.push(element.size);
    });
    setSizesList([...new Set(sizes)]);
  };

  const getOptions = (arr) => {
    const options = arr.map((item) => ({ value: item, label: item }));
    return options;
  };

  const handleChangeSize = (size) => {
    const modelsWithSize = allModels.filter((model) => model.size === size);
    setIsAddedToCart(false);
    setProduct(modelsWithSize[0]);
  };

  const handleChangeDesign = (design) => {
    const modelsWithDesign = allModels.filter(
      (model) => model.design === design
    );
    setIsAddedToCart(false);
    setProduct(modelsWithDesign[0]);
  };

  return (
    <ContainerBase>
      <div className="productContainer">
        <PageTitle
          title={productName}
          isArrow={true}
          arrowGoTo={`/category/${category}`}
        />
        <div className="productPage">
          <div className="productPage__img">
            <div>
              {/* Hacer carousel */}
              <img src={pictureLinks[0]} width="200" />
              <img src={pictureLinks[1]} width="200" />
              <img src={pictureLinks[2]} width="200" />
            </div>
          </div>
          <div className="productPage__description">
            <div className="productPage__description__body">
              <p>{product.price}$</p>
              <p>{productDescription}</p>
            </div>
            <div className="productPage__description__buttons">
              {/* {!sizesList[0] === "PROMOCIONES" && ( */}
              <div>
                <Select
                  options={getOptions(designsList)}
                  placeholder={"Diseño..."}
                  className="select"
                  onChange={(e) => handleChangeDesign(e.value)}
                />
                <Select
                  options={getOptions(sizesList)}
                  placeholder="Talle..."
                  className="select"
                  onChange={(e) => handleChangeSize(e.value)}
                />
              </div>
              {/* )} */}
              <button className="buyBtn" disabled={!userData.auth}>
                Comprar
              </button>
              <button
                className="addBtn"
                disabled={isAddedToCart}
                onClick={handleAddToCart}
              >
                Agregar al carrito
              </button>
              {isAddedToCart && (
                <>
                  <button className="addBtn" onClick={handleDeleteItemFromCart}>
                    Cancelar
                  </button>
                  <p>Este producto ya está en tu carrito</p>
                </>
              )}
              {!userData.auth && (
                <>
                  <p>Registrate e ingresa para comenzar a comprar</p>
                </>
              )}
            </div>
          </div>
        </div>
      </div>
    </ContainerBase>
  );
};

export default ProductPage;
