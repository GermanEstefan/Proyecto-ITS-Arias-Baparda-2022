import React, { useState, useEffect, useContext } from "react";

import { useParams } from "react-router-dom";
import Guantes from "../../assets/img/guantes.jpg";
import PageTitle from "../../components/store/PageTitle";
import ContainerBase from "../../components/store/ContainerBase";
import { fetchApi } from "../../API/api";
import { userStatusContext } from "../../App";
import Select from "react-select";
const ProductPage = () => {
  const { userData } = useContext(userStatusContext);
  const { category, id } = useParams();
  const [cart, setCart] = useState(
    JSON.parse(localStorage.getItem("cart") || [])
  );
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
  useEffect(() => {
    setCart(cart.filter((_barcode) => _barcode !== product.barcode));
  }, [product]);

  const handleAddToCart = () => {
    const list = cart;
    list.push(product.barcode);
    setCart(list);
    localStorage.setItem("cart", JSON.stringify(cart));
    setIsAddedToCart(true);
  };
  const handleDeleteItemFromCart = () => {
    setCart(cart.splice(-1));
    setIsAddedToCart(false);
    // setCart(cart.filter((barcode) => barcode !== barcode));
    // localStorage.setItem("cart", JSON.stringify(cart));
  };

  const getProductById = async () => {
    const resp = await fetchApi(`products.php?idProduct=${id}`, "GET");
    console.log(resp);
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
