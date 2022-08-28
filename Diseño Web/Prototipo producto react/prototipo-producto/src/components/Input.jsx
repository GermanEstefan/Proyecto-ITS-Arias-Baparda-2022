import React, { useState } from "react";

const Input = ({validateFunction, setErrorStatusForm, ...args}) => {

    const [errorState, setErrorState] = useState({ error: false, message: null});

    const validateInput = (e) => {
        if(!validateFunction){
            setErrorState({ error: false, message: null});
            setErrorStatusForm( prevState => {
                return { ...prevState, [e.target.name] : false }
            });
            return;
        } 
        const isValid = validateFunction(e.target.value);
        if(isValid.error){
            setErrorState({ error: true, message: isValid.message});
            setErrorStatusForm( prevState => {
                return { ...prevState, [e.target.name] : true }
            });
        }else{
            setErrorState({ error: false, message: null});
            setErrorStatusForm( prevState => {
                return { ...prevState, [e.target.name] : false }
            });
        }
    }

    return(
        <div className="input-wrapper">
            <input 
                type="text" 
                onFocus={validateInput}
                onBlur={validateInput}
                className="input-wrapper__input"
                style={errorState.error ? {border:'1px solid red'} : {border:'2px solid black'} }
                {...args}
            />
            { errorState.error && <span className="input-wrapper__msg-error" >{errorState.message}</span> }
        </div>
       
    )

}

export default Input;