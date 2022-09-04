import { useEffect, useState } from "react"
import { useNavigate } from "react-router-dom";
import { verifyAuth } from "../helpers/verifyAuth";

const useAuth = (initialState) => {

    const navigate = useNavigate();
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
              auth: true
            });
            navigate('/')
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