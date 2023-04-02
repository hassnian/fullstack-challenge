import DatePicker from "react-multi-date-picker"
import { useState } from "react"

const Filters = () => {

    const [value, setValue] = useState(null)

    const handleDateChange = () => {
        setValue(value)

        //         if (value instanceof DateObject) value = value.toDate()

        //   submitDate(value)
    }

    return (

        <div className="flex ">


            <DatePicker
                value={value}
                onChange={handleDateChange}
            >
            </DatePicker>


        </div>


    );
}

export default Filters;