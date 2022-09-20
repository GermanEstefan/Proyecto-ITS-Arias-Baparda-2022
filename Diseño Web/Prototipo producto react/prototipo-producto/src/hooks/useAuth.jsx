import { useEffect, useState } from "react"
import { verifyAuth } from "../API/api";

const useAuth = (initialState) => {

    const [isChecking, setIsChecking] = useState(false);
    const [userData, setUserData] = useState(initialState);
    
    useEffect(() => {
        setIsChecking(true);
        verifyAuth()
          .then(res => {
            setIsChecking(true);
            if (!res) {
              setIsChecking(false);
              return;
            }
            setUserData({
              name: res.result.data.name,
              surname: res.result.data.surname,
              email: res.result.data.email,
              phone: res.result.data.phone,
              address: res.result.data.address,
              rol : res.result.data.rol,
              auth: true
            });
            setIsChecking(false);
          })
      }, [])
    
    return{
        isChecking,
        userData,
        setUserData
    }
}

export default useAuth;