import Spinner from "./Spinner"

const Button = (props) => {
    return (
        <button
            className="w-full px-5 py-2 text-white transition-all bg-black border border-black hover:bg-white hover:text-black hover:border-black md:w-min"
            onClick={props.onClick}
        >
            {props.isLoading ? (
                <div className="flex justify-center">
                    <Spinner size={6} />
                </div>
            ) : (
                props.children
            )}
        </button>

    );
}

export default Button;