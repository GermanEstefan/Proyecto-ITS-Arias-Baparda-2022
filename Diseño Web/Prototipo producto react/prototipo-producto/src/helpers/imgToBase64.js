
const imgToBase64 = async (file) => {

    return new Promise( (res, rej) => {
        const readFile = new FileReader();
        readFile.readAsDataURL(file);
        readFile.onload = () => {
            res(readFile.result);
        }
        readFile.onerror = () => {
            rej('File cant read');
        }
    }) 

}

export default imgToBase64;