import { useMemo } from "react";
import ArticleCard from "./ArticleCard";


const ArticesHero = ({ articles }) => {

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

        </ul>
    );
}

export default ArticesHero;