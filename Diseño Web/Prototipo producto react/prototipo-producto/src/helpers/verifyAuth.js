import { URL } from "../API/URL";

export const verifyAuth = async () => {
  const tokenInLocalStorage = localStorage.getItem("token") || "";
  console.log("tokenInLocalStorage: " + tokenInLocalStorage);
  try {
    const resp = await fetch(`${URL}auth-employees.php?url=verify`, {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        "access-token": tokenInLocalStorage,
      },
    });
    const respToJson = await resp.json();
    console.log(respToJson)
    
    if (respToJson.status === 'successfully') {
      localStorage.setItem("token", respToJson.result.data.token);
      console.log(respToJson);
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
