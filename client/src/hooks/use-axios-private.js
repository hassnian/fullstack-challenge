import { privateAxios } from "../services/axios";
import { useEffect } from "react";
import { useAuth } from "./use-auth";

const useAxiosPrivate = () => {
    const { token, refresh } = useAuth();

    useEffect(() => {

        const requestIntercept = privateAxios.interceptors.request.use(config => {
            if (!config.headers['Authorization']) {
                config.headers['Authorization'] = `Bearer ${token}`;
            }
            return config;
        }, (error) => Promise.reject(error));

        const responseIntercept = privateAxios.interceptors.response.use(response => response, async (error) => {
            const prevRequest = error?.config;

            const isForbiddenError = error?.response?.status === 403;
            const hasTried = prevRequest?.hasTried;

            if (isForbiddenError && !hasTried) {
                prevRequest.hasTried = true;
                const newAccessToken = await refresh();
                prevRequest.headers['Authorization'] = `Bearer ${newAccessToken}`;
                return privateAxios(prevRequest);
            }

            return Promise.reject(error);
        });

        return () => {
            privateAxios.interceptors.response.eject(responseIntercept);
            privateAxios.interceptors.request.eject(requestIntercept);
        }
    }, [token, refresh])

    return privateAxios;
}

export default useAxiosPrivate;