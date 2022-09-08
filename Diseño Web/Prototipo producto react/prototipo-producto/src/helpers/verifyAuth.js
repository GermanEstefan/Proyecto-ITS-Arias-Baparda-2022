
import { URL } from "../API/URL";

export const verifyAuth = async () => {

  const tokenInLocalStorage = localStorage.getItem("token") || "";

  try {
    const resp = await fetch(`${URL}auth-verify.php`, {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        "access-token": tokenInLocalStorage,
      },
    })
    const respToJson = await resp.json()

    if (respToJson.status === 'successfully') {
      return respToJson;
    } else {
      return null;
    }
  } catch (error) {
    console.log(error);
    return null;
  }

};
