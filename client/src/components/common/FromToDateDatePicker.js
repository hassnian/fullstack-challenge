const FromToDatePicker = (props) => {
    return (
        <div className="flex flex-col md:flex-row">
            <div className="flex flex-col w-full md:w-1/2">
                <label htmlFor="from" className="text-sm font-bold">From</label>
                <input
                    className="w-full"
                    id="from"
                    name="from"
                    type="date"
                    value={props.from}
                    onChange={props.onChange}
                />
            </div>

            <div className="flex flex-col w-full md:w-1/2 md:ml-4">
                <label htmlFor="to" className="text-sm font-bold">To</label>
                <input
                    className="w-full"
                    id="to"
                    name="to"
                    type="date"
                    value={props.to}
                    onChange={props.onChange}
                />
            </div>
        </div>
    );
}

export default FromToDatePicker;