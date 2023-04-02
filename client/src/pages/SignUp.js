import { useMutation } from "@tanstack/react-query";
import AuthForm from "../components/Auth/AuthForm";
import { useMemo } from "react";
import { useAuth } from "../hooks/use-auth";
import { useNavigate } from "react-router-dom";
import ErrorMessage from "../components/common/ErrorMessage";


const Login = () => {

    const { signup } = useAuth()

    let navigate = useNavigate();

    const signupMutation = useMutation({
        mutationFn: signup,
        onSuccess: () => {
            navigate('/login')
        },
        onError: (error) => {
            console.log(error)
        }
    })

    const handleSubmit = (form) => {
        signupMutation.mutate({
            name: form.name,
            email: form.email,
            password: form.password
        })
    }

    const errorMessage = useMemo(() => signupMutation.error?.response?.data?.message || '', [signupMutation.error])
    const errors = useMemo(() => signupMutation.error?.response?.data?.errors || {}, [signupMutation.error])

    return (
        <>
            <div className="flex min-h-full items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
                <div className="w-full max-w-md space-y-8">
                    <div>
                        <h2 className="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900">
                            Sign up for an account
                        </h2>
                    </div>

                    {errorMessage && <ErrorMessage message={errorMessage} />}

                    <AuthForm
                        loading={signupMutation.isLoading}
                        errors={errors}
                        onSubmit={handleSubmit}
                        isSignUp
                    />
                </div>
            </div>
        </>
    )
}

export default Login