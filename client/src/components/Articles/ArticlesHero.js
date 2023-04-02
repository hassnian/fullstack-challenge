import { useMemo } from "react";
import Button from "../common/Button";
import ArticleCard from "./ArticleCard";


const ArticesHero = ({ articles, onLoadMore }) => {

    const articleWithImage = useMemo(() => articles.find((article) => article.image_url), [articles])
    const otherArticles = useMemo(() => articles.filter((article) => article.id !== articleWithImage?.id), [articles, articleWithImage])


    if (!articles) {
        return <></>
    }

    return (
        <ul>
            <div className="grid grid-cols-1 lg:grid-cols-10 lg:gap-3 ">
                <div className="col-span-4 lg:border-r">
                    {articleWithImage && (
                        <div className="sticky top-0">
                            <ArticleCard article={articleWithImage} withImage />
                        </div>
                    )}
                </div>
                <div className="col-span-6">
                    <div className="grid grid-cols-1 md:grid-cols-2 mid:gap-3 ">
                        {otherArticles.map((article) => (
                            <div className="col-span-1" key={article.id}>
                                <ArticleCard article={article} />
                            </div>
                        ))}
                    </div>
                </div>

            </div>




            <div className="flex justify-center py-10">
                {articles.length > 0 ? (
                    <button
                        className="px-4 py-3 text-sm font-bold text-black bg-gray-200 hover:text-white hover:bg-gray-400"
                        onClick={onLoadMore}
                    >
                        Load next page
                    </button>
                )
                    : (<div className="text-center">No more articles</div>)
                }
            </div>

        </ul >
    );
}

export default ArticesHero;