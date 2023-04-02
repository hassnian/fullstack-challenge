import { useState } from "react";
import { Link } from "react-router-dom";
import Spinner from "../common/Spinner";

const AuthForm = (props) => {
    const [form, setForm] = useState({
        name: '',
        email: 'hassnian@gmail.com',
        password: 'password'
    })

    const isSignUp = props.isSignUp

    const handleSubmit = (event) => {
        event.preventDefault();
        props.onSubmit(form)
    }

    const handleChange = (event) => {
        setForm({
            ...form,
            [event.target.name]: event.target.value
        })
    }

    if (props.loading) {
        return (
            <div className="flex justify-center">
                <Spinner />
            </div>
        )
    }

    return (
        <form
            className="space-y-6"
            onSubmit={handleSubmit}
        >

            {isSignUp && (
                <div>
                    <label htmlFor="name" className="sr-only">
                        Name
                    </label>
                    <input
                        className="w-full"
                        id="name"
                        name="name"
                        type="text"
                        autoComplete="name"
                        placeholder="Name"
                        value={form.name}
                        onChange={handleChange}
                    />

                    {props.errors.name && <p className="text-red-500 mt-1">{props.errors.name}</p>}
                </div>
            )}

            <div>
                <label htmlFor="email-address" className="sr-only">
                    Email address
                </label>
                <input
                    className="w-full"
                    id="email-address"
                    name="email"
                    type="email"
                    autoComplete="email"
                    placeholder="Email"
                    value={form.email}
                    onChange={handleChange}
                />

                {props.errors.email && <p className="text-red-500 mt-1">{props.errors.email}</p>}
            </div>

            <div>
                <label htmlFor="password" className="sr-only">
                    Password
                </label>
                <input
                    className="w-full"
                    id="password"
                    name="password"
                    type="password"
                    autoComplete="password"
                    placeholder="Password"
                    value={form.password}
                    onChange={handleChange}
                />

                {props.errors.password && <p className="text-red-500 mt-1">{props.errors.password}</p>}
            </div>

            <div>
                <button
                    type="submit"
                    className="group relative flex w-full justify-center bg-black py-3 px-3 text-sm font-semibold text-white hover:bg-gray-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                >
                    {isSignUp ? 'Sign Up' : 'Sign In'}
                </button>
            </div>

            <div className="flex justify-center">
                <p className="text-sm text-gray-600">
                    {isSignUp ? 'Already have an account?' : "Don't have an account?"}
                    <Link
                        to={isSignUp ? '/login' : '/register'}
                        className="font-medium text-black hover:text-gray-800"
                    >
                        {isSignUp ? ' Sign In' : ' Sign Up'}
                    </Link>
                </p>
            </div>

        </form>
    );
}

export default AuthForm;