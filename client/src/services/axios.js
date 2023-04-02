import axios from 'axios';

const API_URL = 'http://localhost:8000/api';

export const publicAxios = axios.create({ baseURL: API_URL });

export const privateAxios = axios.create({
    baseURL: API_URL,
    headers: { 'Content-Type': 'application/json' },
    // withCredentials: true,
});

