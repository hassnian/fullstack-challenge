import { useMemo } from "react";
import ArticleCard from "./ArticleCard";


const ArticesHero = ({ articles }) => {

    const mainArticle = useMemo(() => articles[0])
    const otherArticles = useMemo(() => articles.slice(1))


    if (!articles) {
        return <></>
    }

    return (
        <ul>
            <div className="grid grid-cols-1 lg:grid-cols-10 lg:gap-3 ">
                <div className="col-span-4 lg:border-r">
                    {mainArticle && (
                        <div className="sticky top-0">
                            <ArticleCard article={mainArticle} withImage withContent />
                        </div>
                    )}
                </div>
                <div className="col-span-6">
                    <div className="grid grid-cols-2 gap-3 ">
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