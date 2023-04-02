import { useMutation } from "@tanstack/react-query";
import AuthForm from "../components/Auth/AuthForm";
import { useMemo } from "react";
import { useAuth } from "../hooks/use-auth";
import { useNavigate, useLocation, useParams } from "react-router-dom";
import ErrorMessage from "../components/common/ErrorMessage";

const Login = () => {

    const { signin } = useAuth()

    let navigate = useNavigate();

    const redirectPath = useParams().redirect || '/'

    const loginMutation = useMutation({
        mutationFn: signin,
        onSuccess: () => {
            navigate(redirectPath)
        },
        onError: (error) => {
            console.log(error)
        }
    })

    const login = (form) => {
        loginMutation.mutate({ email: form.email, password: form.password })
    }

    const errorMessage = useMemo(() => loginMutation.error?.response?.data?.message || '', [loginMutation.error])
    const errors = useMemo(() => loginMutation.error?.response?.data?.errors || {}, [loginMutation.error])

    return (
        <>
            <div className="flex items-center justify-center min-h-full px-4 py-12 sm:px-6 lg:px-8">
                <div className="w-full max-w-md space-y-8">
                    <div>
                        <h2 className="mt-6 text-3xl font-bold tracking-tight text-center text-gray-900">
                            Sign in to your account
                        </h2>
                    </div>

                    {errorMessage && <ErrorMessage message={errorMessage} />}

                    <AuthForm
                        loading={loginMutation.isLoading}
                        errors={errors}
                        onSubmit={login}
                    />
                </div>
            </div>
        </>
    )
}

export default Login