import { Navigate, Outlet } from 'react-router-dom'
import { useAuth } from '../../hooks/use-auth'

const PrivateRoutes = () => {
    const { isSignedIn } = useAuth()

    const getUrlWithRedirect = (url) => {
        return `${url}?redirect=${window.location.pathname}`
    }

    return (
        isSignedIn ? <Outlet /> : <Navigate to={getUrlWithRedirect('/login')} />
    )
}

export default PrivateRoutes