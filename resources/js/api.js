import axios from 'axios';

const DEBUG = true;
const baseURL = 'http://127.0.0.1:8000/api';


const axiosInstance = axios.create({
    baseURL,
    withCredentials: true,
});

axiosInstance.interceptors.request.use(config => {

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    config.headers['X-XSRF-TOKEN'] = csrfToken;

    const auth_token = localStorage.getItem('auth_token');
    if (auth_token) {
        config.headers.Authorization = `Bearer ${auth_token}`;
    }

    return config;
}, (error) => {
    if (DEBUG) { console.error("✉️ ", error); }
    return Promise.reject(error);
});

axiosInstance.interceptors.response.use((ce) => {

    return ce;
}, (error) => {
    if (DEBUG) { console.error("✉️ >>", error); }

    if(error?.response.status === 401) {
        alert("You are not authorized");

        localStorage.setItem('auth_token', "");

        window.location.reload();
    } else if (error?.response.status === 501) {
        alert("Not allow to login from this device.");
    }

    return Promise.reject(error);
});

export default {
    get(endpoint) {
        return axiosInstance.get(endpoint);
    },

    post(endpoint, data) {
        return axiosInstance.post(endpoint, data);
    },

    put(endpoint, data) {
        return axiosInstance.put(endpoint, data);
    },

    delete(endpoint) {
        return axiosInstance.delete(endpoint);
    },
};
