import { Link } from "react-router-dom";
import { useAuth } from "../../hooks/use-auth";

const Navbar = (props) => {

    const { isSignedIn, signout } = useAuth()

    const handleSignout = () => {
        signout()
    }

    return (
        <div className="flex items-center justify-between my-10 space-x-2">

            <div className="space-x-2">
                <Link to="/" className="px-5 py-3 text-black transition-all hover:bg-slate-200">Home</Link>

                <Link to="/feed" className="px-5 py-3 text-black transition-all hover:bg-slate-200">Feed</Link>
            </div>

            {
                !isSignedIn ? (
                    <div className="flex space-x-2">
                        <div>
                            <Link to={'/login'} className="px-5 py-3 text-white bg-black">Login</Link>
                        </div>
                        <div>
                            <Link to="/register" className="px-5 py-3 border border-black">Register</Link>
                        </div>
                    </div>
                ) : (
                    <div className="space-x-3">
                        <Link to="/user" className="px-5 py-3 text-black transition-all hover:bg-slate-200">Preferences</Link>

                        <button onClick={handleSignout} className="px-5 py-3 text-white bg-black">Logout</button>
                    </div>
                )
            }

        </div>

    );
}

export default Navbar;