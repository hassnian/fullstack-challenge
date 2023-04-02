import { useQuery } from "@tanstack/react-query";
import { useState, useMemo, useEffect } from "react";
import { getArticles } from "../services/api";
import ArticlesHero from "../components/Articles/ArticlesHero";
import Spinner from "../components/common/Spinner";
import Filters from "../components/common/Filters";
import Navbar from "../components/common/Navbar";
import DefaultLayout from "../components/common/DefaultLayout";
import ArticleFilterModal from "../components/Articles/ArticleFilterModal";

function HomePage() {

    const [search, setSearch] = useState('')
    const [filters, setFilters] = useState({
        categories: [],
        authors: [],
        datasources: [],
        postedAt: []
    })
    const { isLoading, data: response, isSuccess, refetch } = useQuery(['articles', search, JSON.stringify(filters)],
        () => {

            const { categories, authors, datasources } = filters

            const searchParams = {
                q: search,
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

            if (filters.postedAt.length > 0) {
                searchParams.postedAt = filters.postedAt.map((item) => item.id).join(',')
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
        return response.data.data || []
    }, [isSuccess, response])

    const hasAnyFilters = useMemo(() => {
        console.log(filters)
        return Object.values(filters).length > 0
    }, [filters])

    const title = useMemo(() => {
        if (search) return `Search results for "${search}"`
        return 'Latest Articles'
    }, [search, filters])


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
        refetch({ q: search })
    }, [search])

    return (

        <DefaultLayout>
            < div className="flex items-center justify-between pb-2 ">

                <h1 className="p-4 text-xl font-bold md:text-3xl">{title}</h1>

                <div>
                </div>

                <div className="flex items-center space-x-2">
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
                isLoading && (
                    <div className="flex justify-center w-full p-4">
                        <Spinner />
                    </div>)
            }


            <ArticlesHero
                articles={articles}
            />

        </DefaultLayout>
    );
}

export default HomePage;