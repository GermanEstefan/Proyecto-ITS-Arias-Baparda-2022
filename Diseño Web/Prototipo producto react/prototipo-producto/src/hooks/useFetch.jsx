import { useEffect, useState } from "react";


const useFetch = (endpoint, dependecies) => {
  const [dataState, setDataState] = useState([]);
  const [loading, setLoading] = useState(true);
  useEffect(() => {
    fetch(endpoint)
      .then((res) => res.json())
      .then((resToJson) => setDataState(resToJson))
      .catch((err) => console.log(err))
      .finally(() => setLoading(false));
  }, dependecies);

  return [dataState, loading];

};
export default useFetch;
