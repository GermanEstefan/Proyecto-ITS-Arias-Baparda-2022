export const isEmpty = (value) => {
    if(value.trim().length === 0){
        return {error: true, message: 'Este campo es requerido'}
    }
    return {error: false, message: null};
}

export const isEmail = (value) => {
    const regExp = /[a-z0-9]+@[a-z]+\.[a-z]{2,3}/.test(value);
    if(!regExp) return {error:true, message: 'Debe ser un email valido'}
    return { error:false, message: null};
} 

export const isValidPassword = (value) => {
    if(value.trim().length <= 5){
        return {error:true, message: 'La contraseña debe ser mayor a 5 digitos'}
    }
    return {error:false, message: null};
}