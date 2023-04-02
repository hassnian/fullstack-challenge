import Navbar from "./Navbar";

const DefaultLayout = ({ children }) => {
    return (
        <div className="container px-4 mx-auto">
            <div>
                <Navbar />
            </div>

            <main className="">{children}</main>
        </div>
    )
}

export default DefaultLayout;