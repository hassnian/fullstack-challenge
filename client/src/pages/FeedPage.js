import { useQuery } from "@tanstack/react-query";
import { useMemo, useState } from "react";
import DefaultLayout from "../components/common/DefaultLayout";
import useAxiosPrivate from "../hooks/use-axios-private";
import ArticlesHero from "../components/Articles/ArticlesHero";
import Spinner from "../components/common/Spinner";

const FeedPage = (props) => {
    const privateAxios = useAxiosPrivate()

    const [page, setPage] = useState(1)

    const { isLoading, data } = useQuery(['feed', page], () => {
        return privateAxios.get('/user/feed?page=' + page)
    }, {
        onSuccess: (data) => {
        }
    })

    const articles = useMemo(() => {
        return data?.data?.data
    }, [data])


    return (
        <DefaultLayout>
            <h1 className="p-4 text-xl font-bold md:text-3xl">Feed</h1>

            {
                isLoading ?
                    (
                        <div className="flex items-center w-full p-4">
                            <Spinner />
                        </div>
                    ) :
                    (
                        <ArticlesHero
                            articles={articles}
                            onLoadMore={() => setPage((prev) => prev + 1)}
                        />
                    )
            }



        </DefaultLayout>
    );
}

export default FeedPage;