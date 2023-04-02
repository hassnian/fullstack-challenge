import { publicAxios } from './axios';


const authLogin = (form) => publicAxios.post('/auth/login', form)
const authSignUp = (form) => publicAxios.post('/auth/register', form)

const getArticles = (params) => publicAxios.get('/articles', { params })

export {
    authLogin,
    authSignUp,
    getArticles,
}