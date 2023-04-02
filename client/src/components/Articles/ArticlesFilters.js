import { AsyncTypeahead } from "react-bootstrap-typeahead";
import { useEffect, useMemo, useState } from "react";
import useAxiosPrivate from "../../hooks/use-axios-private";
import FromToDatePicker from "../common/FromToDateDatePicker";

const RemoteInput = (props) => {

    const fetchData = props.fetchData;

    const [isLoading, setIsLoading] = useState(false);
    const [options, setOptions] = useState([]);
    const [selected, setSelected] = useState([]);

    const labelKey = props.labelKey || 'name';
    const minLength = props.minLength || 1
    const placeholder = props.placeholder || 'Search'
    const id = props.id || 'id'

    useEffect(() => {
        if (props.value) {
            setSelected(props.value)
        }
    }, [props.value])

    const handleSearch = (query) => {
        setIsLoading(true);

        fetchData(query)
            .then((items) => {
                setOptions(items);
                setIsLoading(false);
            });
    };

    const filterBy = () => true;

    const getUnique = array => {
        const uniqueArray = array.map(e => e['id'])

            .map((e, i, final) => final.indexOf(e) === i && i)

            .filter((e) => array[e]).map(e => array[e]);

        return uniqueArray;
    }

    const removeSelected = (option) => {
        setSelected((prev) => {
            return prev.filter((item) => item.id !== option.id)
        })
    }

    const avilableOptions = useMemo(() => {
        return selected.length > 0 ? options.filter((option) => !selected.find((item) => item.id === option.id)) : options
    }, [options, selected])

    const renderMenu = (results, menuProps) => {
        return (
            <div {...menuProps} className='z-20 mt-10 bg-white border-2 border-black'>
                {results.length === 0 ? (
                    <div className="p-2 ">{menuProps.emptyLabel || 'No results found'}</div>
                ) : (
                    results.map((result) => (
                        <div className="flex py-2 cursor-pointer hover:bg-slate-200"
                            onClick={() => {
                                setSelected((prev) => {
                                    return getUnique([...prev, result])
                                })
                            }}
                        >
                            <div className="px-2 font-semibold"
                            >{result[labelKey]}</div>
                        </div>
                    ))
                )}
            </div>
        )
    }

    useEffect(() => {
        props.onChange(selected)
    }, [selected])

    return (
        <div>
            <AsyncTypeahead
                id={id}
                filterBy={filterBy}
                isLoading={isLoading}
                labelKey={labelKey}
                minLength={minLength}
                renderMenu={renderMenu}
                onSearch={handleSearch}
                options={avilableOptions}
                placeholder={placeholder}

            />

            <div className="flex mt-4 space-x-2">
                {selected.map((option) => (
                    <div key={option.id} className="flex items-center px-4 py-2 text-sm font-semibold text-black border-2 border-black border-dashed">
                        <div className="mr-2">{option.name}</div>
                        <div
                            className="cursor-pointer"
                            onClick={() => {
                                removeSelected(option)
                            }}
                        >x</div>
                    </div>
                ))
                }
            </div>
        </div>
    )

}



const ArticleFilters = (props) => {
    const axios = useAxiosPrivate()

    const defaultFilters = {
        categories: [],
        authors: [],
        datasources: [],
        publishedAt: {
            from: null,
            to: null
        }
    }

    const withPublishedAt = props.withPostedAt || false

    const [filters, setFilters] = useState(defaultFilters)
    const preselected = useMemo(() => props.preselected || defaultFilters, [props.preselected])

    const fetchCategories = (query) => {
        return axios.get('/categories', {
            params: { q: query }
        })
            .then((json) => { return json.data.data })
    }

    const fetchAuthors = (query) => {
        return axios.get('/authors', {
            params: { q: query }
        }).then((json) => { return json.data.data })
    }

    const fetchDatasources = (query) => {
        return axios.get('/datasources', { params: { q: query } })
            .then((json) => { return json.data.data })
    }

    const handleFilterChange = (value, key) => {
        setFilters((prev) => {
            return { ...prev, [key]: value }
        })
    }

    const handleDatePickerChange = (event) => {
        const value = event.target.valueAsDate
        const name = event.target.name

        setFilters((prev) => {
            return { ...prev, publishedAt: { ...prev.publishedAt, [name]: value } }
        })
    }

    useEffect(() => {
        props.onChange(filters)
    }, [filters])

    return (

        <div className="space-y-6">

            <div className="space-y-2">
                <p className="text-xl font-bold ">Categories</p>
                <RemoteInput
                    id="categories"
                    value={preselected.categories}
                    fetchData={fetchCategories}
                    placeholder="Search by name"
                    onChange={(value) => handleFilterChange(value, 'categories')}
                />
            </div>

            <div className="space-y-2">
                <p className="text-xl font-bold ">Authors</p>
                <RemoteInput
                    id="authors"
                    value={preselected.authors}
                    fetchData={fetchAuthors}
                    placeholder="Search by name"
                    onChange={(value) => handleFilterChange(value, 'authors')}
                />
            </div>


            <div className="space-y-2">
                <p className="text-xl font-bold ">Datasources</p>
                <RemoteInput
                    id="datasources"
                    value={preselected.datasources}
                    fetchData={fetchDatasources}
                    placeholder="Search by name"
                    onChange={(value) => handleFilterChange(value, 'datasources')}
                />
            </div>

            {withPublishedAt && (
                <div className="space-y-2">
                    <p className="text-xl font-bold ">Posted At</p>

                    <FromToDatePicker
                        onChange={handleDatePickerChange}
                    />
                </div>
            )}

        </div>


    );
}

export default ArticleFilters;