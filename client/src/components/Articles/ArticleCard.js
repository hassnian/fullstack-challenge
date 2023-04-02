import { Link } from "react-router-dom"

const ArticleCard = ({ article, withImage = false }) => {

    const formattedDate = new Date(article.published_at).toLocaleDateString('en-US', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    })

    return (
        <div className="flex flex-col p-4">
            {withImage && <img src={article.image_url} alt={article.title} loading="lazy" className="object-cover w-full h-64" />}

            <time className="pt-4 text-gray-500">{formattedDate}</time>

            <h3 className="py-2 text-xl font-bold md:text-2xl">{article.title}</h3>

            <p className="py-2 text-gray-500">{article.content}</p>

            <Link to={article.source_url} target="_blank" className="pt-2 text-sm font-semibold text-blue-700 underline">
                Continue Reading
            </Link>
        </div>
    )
}

export default ArticleCard