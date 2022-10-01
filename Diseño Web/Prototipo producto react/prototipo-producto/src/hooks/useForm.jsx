import { useState } from "react";

export const useForm = (initState) => {
  const [values, setValues] = useState(initState);

  const handleValuesChange = ({ target }) => {
    if (target.name === "type") {
        console.log('useForm if: ' + target.name +  ' ' + target.value)
      setValues({
        ...values,
        [target.name]: target.value ? "COMPANY" : "NORMAL",
      });
    }
    if (target.name !== "type") {
      setValues({
        ...values,
        [target.name]: target.value,
      });
    }
    console.log(values);
  };

  const resetForm = () => setValues(initState);

  return [values, handleValuesChange, resetForm];
};
