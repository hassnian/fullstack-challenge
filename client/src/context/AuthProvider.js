import { useState, useContext, createContext, useMemo, useEffect } from "react";
import { authLogin, authSignUp } from "../services/api";
// import useAxiosPrivate from "../hooks/use-axios-private";
import { privateAxios } from "../services/axios";

const useProvideAuth = () => {
    const [user, setUser] = useState(null);
    const [token, setToken] = useState(null);

    const isSignedIn = useMemo(() => !!token, [token])

    const signin = async ({ email, password }) => {
        const response = await authLogin({ email, password })
        const { data } = response

        setToken(data.access_token)

        return response
    };

    const signup = async ({ email, password, name }) => {
        const response = await authSignUp({ name, email, password })

        return response
    };

    const signout = () => {
        setToken(null)
        setUser(null)
    };

    const getUser = async () => {

        const response = await privateAxios.get('/auth/me', {
            headers: {
                Authorization: `Bearer ${token}`
            }
        })

        const user = response.data.data

        setUser(user)
    };

    const refresh = async () => {
        // return axios.get('/auth/user')
    }

    return {
        token,
        user,
        isSignedIn,
        signin,
        signup,
        signout,
        getUser,
        refresh
    };
}

export const AuthContext = createContext();

export default function ProvideAuth({ children }) {
    const auth = useProvideAuth();

    useEffect(() => {
        if (auth.isSignedIn) {
            auth.getUser()
        }
    }, [auth.isSignedIn])

    return (
        <AuthContext.Provider value={auth}>
            {children}
        </AuthContext.Provider>
    );
}