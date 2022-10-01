
const urlBase = process.env.REACT_APP_API_URL === 'PROD' ? window.origin + '/api/' : process.env.REACT_APP_API_URL

export const fetchApi = async (endpoint, method, data) => {
    const resp = await fetch(`${urlBase}${endpoint}`, {
        method,
        headers: {
            'access-token': localStorage.getItem('token') || ''
        },
        body: JSON.stringify(data)
    });
    const respToJson = await resp.json();
    return respToJson;
}

export const verifyAuth = async () => {
    try {
        const resp = await fetchApi("auth-verify.php", "GET")
        if (resp.status === 'successfully') {
            return resp;
        } else {
            return null;
        }
    } catch (error) {
        console.log(error);
        return null;
    }

};
