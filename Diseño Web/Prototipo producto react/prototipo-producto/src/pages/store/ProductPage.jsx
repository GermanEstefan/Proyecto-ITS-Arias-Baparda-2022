/** @format */

import React, { useState, useEffect, useContext } from "react";

import { useParams } from "react-router-dom";
import PageTitle from "../../components/store/PageTitle";
import ContainerBase from "../../components/store/ContainerBase";
import { fetchApi } from "../../API/api";
import { cartContext, userStatusContext } from "../../App";
import Select from "react-select";
import NoPhoto from "../../assets/img/no-photo.png";
import { useNavigate } from "react-router-dom";
const ProductPage = () => {
  const navigate = useNavigate();
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
  const [img, setImg] = useState("");
  const [quantitySelected, setQuantitySelected] = useState(1);
  const [isEnoughStock, setIsEnoughStock] = useState(true);

  useEffect(() => {
    window.scroll(0, 0);
    getProductById();
  }, []);
  useEffect(() => {
    setIsEnoughStock(quantitySelected <= parseInt(product.stock));
    setIsAddedToCart(
      cart.filter((cartProduct) => cartProduct.barcode === product.barcode).length > 0
    );
  }, [product, quantitySelected]);

  const handleAddToCart = () => {
    if (!isAddedToCart) {
      setCart([
        ...cart,
        { barcode: product.barcode, quantity: quantitySelected, price: product.price },
      ]);
    }

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
    setImg(resp.result.data.picture);
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
    const modelsWithDesign = allModels.filter((model) => model.design === design);
    setIsAddedToCart(false);
    setProduct(modelsWithDesign[0]);
  };

  return (
    <ContainerBase>
      <div className="productContainer">
        <PageTitle title={productName} isArrow={true} arrowGoTo={`/category/${category}`} />
        <div className="productPage">
          <div className="productPage__img">
            <div>
              <img src={img || NoPhoto} width="200" />
            </div>
          </div>
          <div className="productPage__description">
            <div className="productPage__description__body">
              <p>{product.price}$</p>
              <p>{productDescription}</p>
            </div>
            <div className="productPage__description__buttons">
              <div className="selectsContainer">
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
              <div className="buttonsFlexContainer">
                <div className="quantityInputContainer">
                  <span>Cantidad:</span>

                  <input
                    type="number"
                    onChange={(e) => {
                      setQuantitySelected(e.target.value);
                      setIsEnoughStock(quantitySelected <= parseInt(product.stock));
                    }}
                    min={1}
                    defaultValue={1}
                  />
                </div>
                <div className="buyAndAddToCartContainer">
                  <button
                    className="buyBtn"
                    disabled={!userData.auth || !isEnoughStock}
                    onClick={() => {
                      handleAddToCart();
                      navigate("/shoppingCart");
                    }}
                  >
                    Comprar
                  </button>
                  <button
                    className="addBtn"
                    disabled={isAddedToCart || !isEnoughStock}
                    onClick={handleAddToCart}
                  >
                    Agregar al carrito
                  </button>
                  {isAddedToCart && (
                    <button className="addBtn" onClick={handleDeleteItemFromCart}>
                      Cancelar
                    </button>
                  )}
                </div>
              </div>
              {isAddedToCart && <p>Este producto ya está en tu carrito</p>}
              {!userData.auth && <p>Registrate e ingresa para comenzar a comprar</p>}
              {!isEnoughStock && <p>Maximo disponible: {product.stock}</p>}
            </div>
          </div>
        </div>
      </div>
    </ContainerBase>
  );
};

export default ProductPage;
