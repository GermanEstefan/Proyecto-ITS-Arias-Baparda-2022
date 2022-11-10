import { useState } from "react";

export const useForm = (initState) => {
  const [values, setValues] = useState(initState);

  const handleValuesChange = ({ target }) => {
    if (target.name === "type") {
      if (target.checked) {
        setValues({
          ...values,
          [target.name]: "COMPANY",
        });
      }
      if (!target.checked) {
        setValues({
          ...values,
          [target.name]: "NORMAL",
        });
        //Quitamos los campos company y nRut del objeto
        setValues((current) => {
          const { company, nRut, ...rest } = current;
          return rest;
        });
      }
    }
    if (target.name !== "type") {
      setValues({
        ...values,
        [target.name]: target.value,
      });
      
    }
  };

  const resetForm = () => setValues(initState);

  return [values, handleValuesChange, resetForm];
};
