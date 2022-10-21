import React, { useState, useEffect, useContext } from "react";

import { useParams } from "react-router-dom";
import Guantes from "../../assets/img/guantes.jpg";
import PageTitle from "../../components/store/PageTitle";
import ContainerBase from "../../components/store/ContainerBase";
import { fetchApi } from "../../API/api";
import { cartContext, userStatusContext } from "../../App";
import Select from "react-select";
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

  useEffect(() => {
    window.scroll(0, 0);
    getProductById();
  }, []);

  // useEffect(() => {
  //   setCart(cart.filter(({barcode}) => barcode !== product.barcode));
  // }, [product]);

  const handleAddToCart = () => {
    setCart([...cart, { barcode: product.barcode, amount: 1 }]);
    setIsAddedToCart(true);
  };
  const handleDeleteItemFromCart = () => {
    const newCart = cart;
    newCart.pop();
    console.log(newCart)
    setCart(newCart);
    setIsAddedToCart(false);
  };

  const getProductById = async () => {
    const resp = await fetchApi(`products.php?idProduct=${id}`, "GET");
    setProduct(resp.result.data.models[0] ? resp.result.data.models[0] : {});
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
              <img width={"300px"} src={Guantes} alt="Imagen del producto" />
            </div>
          </div>
          <div className="productPage__description">
            <div className="productPage__description__body">
              <p>{product.price}$</p>
              <p>{productDescription}</p>
            </div>
            <div className="productPage__description__buttons">
              {!sizesList[0] === "PROMOCIONES" && (
                <div>
                  <Select
                    options={getOptions(designsList)}
                    placeholder={"Diseño..."}
                    className="select"
                  />
                  <Select
                    options={getOptions(sizesList)}
                    placeholder="Talle..."
                    className="select"
                  />
                </div>
              )}
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
