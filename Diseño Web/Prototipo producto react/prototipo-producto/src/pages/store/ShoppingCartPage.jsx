import React, { useEffect, useState } from "react";
import Guantes from "../../assets/img/guantes.jpg";
import PageTitle from "../../components/store/PageTitle";
import ContainerBase from "../../components/store/ContainerBase";
import CartItem from "../../components/store/CartItem";
import CartDetails from "../../components/store/CartDetails";
import { fetchApi } from "../../API/api";
import { useContext } from "react";
import { cartContext } from "../../App";

const ShoppingCartPage = () => {
  const { cart } = useContext(cartContext);

  const [total, setTotal] = useState(0);

  const [productsList, setProductsList] = useState([]);
  useEffect(() => {
    window.scroll(0, 0);
    getProductsListByBarcode();
  }, []);
  useEffect(() => {
    setTotalPrice();
  }, [productsList]);

  const getProductsListByBarcode = async () => {
    const promises = [];
    cart.map(({ barcode }) => {
      promises.push(fetchApi(`products.php?barcode=${barcode}`, "GET"));
    });
    const responses = Promise.all(promises);
    const products = await responses;

    const productsData = products.map((product) => product.result.data);

    // Agregamos la cantidad de productos que tiene cada producto en el carrito a el objeto data
    cart.map(({ amount }, index) => (productsData[index]["amount"] = amount));

    setProductsList(productsData);
    setTotalPrice();
  };

  const setTotalPrice = () => {
    setTotal(
      productsList.length > 0 &&
        productsList
          .map((product) => product.price * product.amount)
          .reduce((a, b) => parseInt(a) + parseInt(b))
    );
    console.log(total);
  };

  //Implementar que en el carrito también se guarde la cantidad de productos que hay

  return (
    <ContainerBase>
      <div className="productContainer">
        <PageTitle title={"Carrito"} isArrow={true} arrowGoTo={`/`} />
        <div className="cartPage">
          <CartDetails total={total || 0} />
          {productsList.map((product, index) => (
            <CartItem
              key={index}
              img={Guantes}
              barcode={product.barcode}
              name={product.name}
              price={product.price}
              amount={product.amount}
              setTotalPrice={setTotalPrice}
            />
          ))}
          {productsList.length === 0 && (
            <span className="center">No tienes productos en tu carrito</span>
          )}
        </div>
      </div>
    </ContainerBase>
  );
};

export default ShoppingCartPage;
