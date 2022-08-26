
import { URL } from "../API/URL";

export const verifyAuth = async () => {
  

  const tokenInLocalStorage = localStorage.getItem("token") || "";
  console.log("tokenInLocalStorage: " + tokenInLocalStorage);
  try {
    const resp = await fetch(`${URL}auth-verify.php`, {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        "access-token": tokenInLocalStorage,
      },
    }).catch((error) => {
      console.error(error)
    });
    const respToJson = await resp.json()
    console.log(respToJson)
    if (respToJson.status === 'successfully') {
      
      return respToJson;
    } else {
      return null;
    }
  } catch (error) {
    console.warn(
      "Internal error, please check your internet connection",
      error
    );
  }
};
