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
  }, []);

  const [cart, setCart] = useState(() => {
    const saved = localStorage.getItem("cart");
    const initialValue = JSON.parse(saved);
    return initialValue || [];
  });

  //promise.ALL

  //Traemos la lista de productos del usuario

  const productList = [
    {
      name: "tuki",
      precio: 100,
      description:
        "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quidem consequuntur animi aliquid nulla rem nostrum nesciunt voluptas ea quos quo cum, ratione non voluptatibus! Iure recusandae officiis nostrum quasi dolor.",
    },
    {
      name: "flama",
      precio: 100,
      description:
        "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quidem consequuntur animi aliquid nulla rem nostrum nesciunt voluptas ea quos quo cum, ratione non voluptatibus! Iure recusandae officiis nostrum quasi dolor.",
    },
    {
      name: "joya",
      precio: 100,
      description:
        "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quidem consequuntur animi aliquid nulla rem nostrum nesciunt voluptas ea quos quo cum, ratione non voluptatibus! Iure recusandae officiis nostrum quasi dolor.",
    },
    {
      name: "fiera",
      precio: 100,
      description:
        "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quidem consequuntur animi aliquid nulla rem nostrum nesciunt voluptas ea quos quo cum, ratione non voluptatibus! Iure recusandae officiis nostrum quasi dolor.",
    },
    {
      name: "godines",
      precio: 100,
      description:
        "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quidem consequuntur animi aliquid nulla rem nostrum nesciunt voluptas ea quos quo cum, ratione non voluptatibus! Iure recusandae officiis nostrum quasi dolor.",
    },
  ];

  const total = productList
    .map((product) => product.precio)
    .reduce((a, b) => a + b);

  return (
    <ContainerBase>
      <div className="productContainer">
        <PageTitle title={"Carrito"} isArrow={true} arrowGoTo={`/`} />
        <div className="cartPage">
          <CartDetails total={total} />
          {cart.map((product, index) => (
            <CartItem
              index={index}
              product={product.barcode}
              img={Guantes}
              barcode={product.barcode}
            />
          ))}
        </div>
      </div>
    </ContainerBase>
  );
};

export default ShoppingCartPage;
