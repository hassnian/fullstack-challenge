import { useQuery } from "@tanstack/react-query";
import { useState, useMemo, useEffect } from "react";
import { getArticles } from "../services/api";
import ArticlesHero from "../components/Articles/ArticlesHero";
import Spinner from "../components/common/Spinner";
import DefaultLayout from "../components/common/DefaultLayout";
import ArticleFilterModal from "../components/Articles/ArticleFilterModal";
import useDebounce from "../hooks/use-debounce";

const getFormattedDate = (date) => {
    const year = date.getFullYear()
    const month = date.getMonth() + 1
    const day = date.getDate()

    return `${year}-${month}-${day}`
}

function HomePage() {

    const initialFilterState = {
        categories: [],
        authors: [],
        datasources: [],
        publishedAt: {}
    }

    const [page, setPage] = useState(1)
    const [search, setSearch] = useState('')
    const debouncedSearch = useDebounce(search, 500);
    const [filters, setFilters] = useState(initialFilterState)


    const { isLoading, data: response, isSuccess, refetch } = useQuery(['articles', debouncedSearch, page, JSON.stringify(filters)],
        () => {

            const { categories, authors, datasources } = filters

            const searchParams = {
                q: debouncedSearch,
                page: page
            }

            if (categories.length > 0) {
                searchParams.categories = categories.map((item) => item.id).join(',')
            }

            if (authors.length > 0) {
                searchParams.authors = authors.map((item) => item.id).join(',')
            }

            if (datasources.length > 0) {
                searchParams.datasources = datasources.map((item) => item.datasource_id).join(',')
            }

            if (filters.publishedAt.from) {
                const { from, to } = filters.publishedAt

                if (!to) searchParams.publishedAt = getFormattedDate(from)

                searchParams.publishedAt = `${getFormattedDate(from)}:${getFormattedDate(to)}`
            }

            return getArticles(searchParams);
        },
        {
            onError: (error) => {
                console.log(error)
            }
        }

    )


    const articles = useMemo(() => {
        if (!isSuccess) return []
        return response?.data?.data || []
    }, [isSuccess, response])

    const hasAnyFilters = useMemo(() => {
        return filters.categories.length > 0 || filters.authors.length > 0 || filters.datasources.length > 0 || filters.publishedAt.from
    }, [filters])

    const title = useMemo(() => {
        if (search) return `Search results for "${search}"`
        return 'Latest Articles'
    }, [search, filters])

    const clearFilters = () => {
        setFilters(initialFilterState)
    }

    const handleFilterChange = (filters) => {
        setFilters((prev) => {
            return {
                ...prev,
                ...filters
            }
        }
        )
    }

    useEffect(() => {
        setPage(1)
    }, [debouncedSearch, filters])

    return (

        <DefaultLayout>
            < div className="flex items-center justify-between pb-2 ">

                <h1 className="p-4 text-xl font-bold md:text-3xl">{title}</h1>

                <div>
                </div>

                <div className="flex items-center space-x-2">

                    {hasAnyFilters && (
                        <button
                            className="px-4 py-3 text-sm font-bold text-white bg-red-500 "
                            onClick={clearFilters}
                        >
                            Clear Filters
                        </button>
                    )
                    }

                    <ArticleFilterModal
                        onChange={handleFilterChange}
                    />

                    <div>
                        <input
                            type='search'
                            placeholder='Search'
                            value={search}
                            onChange={(e) => setSearch(e.target.value)}
                        />
                    </div>

                </div>
            </div>

            {
                isLoading ? (
                    <div className="flex justify-center w-full p-4">
                        <Spinner />
                    </div>) : (
                    <div>
                        <ArticlesHero
                            articles={articles}
                            onLoadMore={() => setPage((prev) => prev + 1)}
                        />
                    </div>
                )
            }


        </DefaultLayout>
    );
}

export default HomePage;