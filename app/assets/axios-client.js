import axios from "axios";

const axiosClient = axios.create({
    // baseURL: '/'
    headers: {Accept: 'application/json'}
})

export default axiosClient;