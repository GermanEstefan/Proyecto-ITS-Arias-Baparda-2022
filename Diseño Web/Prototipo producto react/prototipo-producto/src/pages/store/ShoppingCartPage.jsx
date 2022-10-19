import React, { useEffect, useState } from "react";
import Guantes from "../../assets/img/guantes.jpg";
import PageTitle from "../../components/store/PageTitle";
import ContainerBase from "../../components/store/ContainerBase";
import CartItem from "../../components/store/CartItem";
import CartDetails from "../../components/store/CartDetails";
import { fetchApi } from "../../API/api";

const ShoppingCartPage = () => {
  useEffect(() => {
    window.scroll(0, 0);
    getProductsListByBarcode();
  }, []);
  const [cart, setCart] = useState(
    JSON.parse(localStorage.getItem("cart") || [])
  );

  const [total, setTotal] = useState(0);

  const [productsList, setProductsList] = useState([]);

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

    setTotal(
      productsData
        .map((product) => product.price * product.amount)
        .reduce((a, b) => parseInt(a) + parseInt(b))
    );
    setProductsList(productsData);
  };



  //Implementar que en el carrito también se guarde la cantidad de productos que hay

  return (
    <ContainerBase>
      <div className="productContainer">
        <PageTitle title={"Carrito"} isArrow={true} arrowGoTo={`/`} />
        <div className="cartPage">
          <CartDetails total={total} />
          {productsList.map((product, index) => (
            <CartItem
              key={index}
              img={Guantes}
              barcode={product.barcode}
              name={product.name}
              price={product.price}
              amount={product.amount}
            />
          ))}
        </div>
      </div>
    </ContainerBase>
  );
};

export default ShoppingCartPage;
